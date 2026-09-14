<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Auth & Public Controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ChatbotController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\BundleController;
use App\Http\Controllers\Admin\TransaksiController as AdminTransaksi;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\LaporanController;

// Customer Controllers
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
use App\Http\Controllers\Customer\KatalogController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\AjukanReturn;
use App\Http\Controllers\Customer\CustomerOrderController;

// Teknisi Controllers
use App\Http\Controllers\Teknisi\DashboardController as TeknisiDashboard;
use App\Http\Controllers\Teknisi\OrderController as TeknisiOrder;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- PUBLIC ROUTES ---
Route::get('/', function (\Illuminate\Http\Request $request) {
    if (auth()->check()) {
        return redirect()->route('customer.katalog'); 
    }
    return app(FrontController::class)->index($request);
});

// --- AUTHENTICATION ---
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');
});


// --- ADMIN PANEL (Protected) ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard & Master Data
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('barang', BarangController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('users', UserController::class);

    // Bundle / Rakitan
    Route::get('/bundle/{id}/edit', [BundleController::class, 'edit'])->name('bundle.edit');
    Route::put('/bundle/{id}', [BundleController::class, 'update'])->name('bundle.update');
    Route::post('/bundle/{id}/build', [BundleController::class, 'build'])->name('bundle.build');

    // Transaksi / Orders Master
    Route::get('/transaksi', [AdminTransaksi::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/process', [AdminTransaksi::class, 'store'])->name('transaksi.store');
    Route::get('/transaksi/riwayat', [AdminTransaksi::class, 'riwayat'])->name('transaksi.riwayat');
    Route::post('/transaksi/{id}/assign-teknisi', [AdminTransaksi::class, 'assignTeknisi'])->name('transaksi.assignTeknisi');
    Route::get('/transaksi/{id}/download-garansi', [AdminTransaksi::class, 'downloadDokumenGaransi'])->name('transaksi.download_garansi');

    // Order Actions
    Route::get('/orders', [AdminTransaksi::class, 'riwayat'])->name('orders.index');
    Route::get('/orders/{id}', [AdminTransaksi::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/status', [AdminTransaksi::class, 'updateStatus'])->name('orders.update_status');
    Route::get('/orders/{id}/print-offering', [AdminTransaksi::class, 'printOffering'])->name('orders.print_offering');
    Route::get('/orders/{id}/print-invoice', [AdminTransaksi::class, 'printInvoice'])->name('orders.print_invoice');
    Route::post('/orders/{id}/ongkir', [AdminTransaksi::class, 'updateOngkir'])->name('orders.update_ongkir');
    Route::post('/orders/{id}/acc', [AdminTransaksi::class, 'accPembayaran'])->name('orders.acc_pembayaran');
    Route::post('/orders/{id}/tagih-pelunasan', [AdminTransaksi::class, 'tagihPelunasan'])->name('orders.tagih_pelunasan');
    Route::post('/orders/{id}/konfirmasi-pelunasan', [AdminTransaksi::class, 'konfirmasiPelunasan'])->name('orders.konfirmasi_pelunasan');
    Route::post('/orders/{id}/kirim-estimasi', [AdminTransaksi::class, 'kirimEstimasi'])->name('orders.kirim_estimasi');
    Route::post('/orders/{id}/kirim-paket', [AdminTransaksi::class, 'kirimPaket'])->name('orders.kirim_paket');
    Route::post('/orders/{id}/konfirmasi-selesai', [AdminTransaksi::class, 'konfirmasiSelesai'])->name('orders.konfirmasi_selesai');
    Route::post('/orders/{id}/tolak-pembayaran', [AdminTransaksi::class, 'tolakPembayaran'])->name('orders.tolak_pembayaran');
    Route::post('/orders/{id}/tolak-pelunasan', [AdminTransaksi::class, 'tolakPelunasan'])->name('orders.tolak_pelunasan');

    // Retur
    Route::post('/retur/{id}/approve', [AdminTransaksi::class, 'approveRetur'])->name('retur.approve');
    Route::post('/retur/{id}/reject', [AdminTransaksi::class, 'rejectRetur'])->name('retur.reject');
    Route::post('/retur/{id}/terima', [AdminTransaksi::class, 'terimaPaket'])->name('retur.terima_paket');
    Route::post('/retur/{id}/hasil-cek', [AdminTransaksi::class, 'hasilCekTeknisi'])->name('retur.hasil_cek');

    // Logs & Laporan
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('logs.index');
    Route::get('/logs/cetak-pdf', [ActivityLogController::class, 'cetakPdf'])->name('logs.cetak_pdf');
    Route::get('/laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
    Route::get('/laporan/cetak-pdf', [LaporanController::class, 'cetakPdf'])->name('laporan.cetak_pdf');
});


// --- CUSTOMER PANEL (Protected) ---
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    
    // Dashboard & Profile
    Route::get('/dashboard', [CustomerDashboard::class, 'index'])->name('dashboard');
    Route::get('/profile', [CustomerDashboard::class, 'profile'])->name('profile');
    Route::put('/profile/update-info', [CustomerDashboard::class, 'updateInfo'])->name('profile.update_info');
    Route::put('/profile/update-password', [CustomerDashboard::class, 'updatePassword'])->name('profile.update_password');

    // Katalog
    Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog');
    Route::get('/katalog/{id}/detail', [KatalogController::class, 'show'])->name('katalog.detail');
    
    // Chatbot AI
    Route::post('/chatbot/ask', [ChatbotController::class, 'askGemini'])->name('chatbot.ask');

    // Cart System
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/add-custom', [CartController::class, 'addCustom'])->name('cart.add_custom');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/bundle', [CartController::class, 'addBundle'])->name('cart.bundle');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::get('/transaksi/{id}/download-garansi', [AdminTransaksi::class, 'downloadDokumenGaransi'])->name('transaksi.download_garansi');

    // Order Actions
    Route::get('/order/{id}', [CustomerDashboard::class, 'show'])->name('orders.show');
    Route::post('/order/{id}/upload-bukti', [CustomerDashboard::class, 'uploadBukti'])->name('upload_bukti');
    Route::post('/order/{id}/batal', [CartController::class, 'batalkanPesanan'])->name('cart.batal');
    Route::post('/orders/{id}/acc-po', [CartController::class, 'accPO'])->name('cart.acc_po');
    Route::post('/orders/{id}/tolak-po', [CartController::class, 'tolakPO'])->name('cart.tolak_po');
    Route::post('/orders/{id}/revisi-po', [CartController::class, 'revisiPO'])->name('cart.revisi_po');
    Route::post('/orders/{id}/selesai', [CartController::class, 'pesananSelesai'])->name('orders.selesai');
    Route::get('/orders/{id}/print-offering', [CartController::class, 'printOffering'])->name('orders.print_offering');
    Route::get('/orders/{id}/print-invoice', [CartController::class, 'printInvoice'])->name('orders.print_invoice');
    Route::get('/transaksi/{id}/garansi-qc', [CustomerOrderController::class, 'cetakGaransiQc'])->name('transaksi.cetak_garansi_qc');

    // Retur Customer
    Route::post('/orders/{id}/retur', [CartController::class, 'ajukanRetur'])->name('orders.ajukan_retur');
    Route::post('/order/{id}/ajukan-retur', [AjukanReturn::class, 'store'])->name('orders.store_retur');
    Route::post('/retur/{id}/kirim-resi', [AjukanReturn::class, 'kirimResi'])->name('retur.kirim_resi');
});


// --- TEKNISI PANEL (Protected) ---
Route::middleware(['auth'])->prefix('teknisi')->name('teknisi.')->group(function () {
    Route::get('/dashboard', [TeknisiDashboard::class, 'index'])->name('dashboard');
    Route::get('/orders', [TeknisiOrder::class, 'index'])->name('orders.index');
    Route::get('/history', [TeknisiOrder::class, 'history'])->name('history');
    Route::get('/orders/{id}', [TeknisiOrder::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/qc', [TeknisiOrder::class, 'storeQc'])->name('orders.storeQc');
    Route::post('/transaksi/{id}/otw', [TeknisiOrder::class, 'tandaiOtw'])->name('tandaiOtw');
    Route::post('/transaksi/{id}/selesai', [TeknisiOrder::class, 'selesaikanTugas'])->name('selesaikanTugas');
    Route::get('/produksi/garansi/{log_id}/unit/{nomor_unit}', [TeknisiOrder::class, 'cetakGaransiProduksi'])->name('produksi.cetak_garansi');
});