<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;

class KatalogController extends Controller
{
public function index(Request $request)
{
    // Eager load relasi components & details biar nggak berat
    $query = Barang::with(['components', 'details'])->where('stok', '>', 0);
    
    if ($request->filled('search')) {
        $query->where('nama_barang', 'like', '%' . $request->search . '%');
    }
    
    if ($request->filled('kategori')) {
        $query->where('kategori', $request->kategori);
    }
    
    $katalog = $query->orderBy('created_at', 'desc')->get();

    // 👇 KELOMPOK KATEGORI UTAMA & SUB-KATEGORINYA 👇
    $komponenList  = ['Processor', 'VGA / GPU', 'Motherboard', 'RAM', 'Storage', 'PSU', 'Casing', 'Cooler'];
    $computerList  = ['PC Rakitan / Build', 'Laptop', 'Mini PC'];
    $aksesorisList = ['Monitor', 'Keyboard & Mouse', 'Accessories'];

    $rekomendasiBuild = [];
    $totalHargaBuild  = 0;
    $rekomendasiTerlaris = collect();

    // Bagian rekomendasi hanya dimuat jika pencarian & filter kategori kosong
    if (!$request->filled('search') && !$request->filled('kategori')) {
        
        // 1. QUERY TOP 1 TERLARIS PER KATEGORI
        $semuaBarangLaris = \Illuminate\Support\Facades\DB::table('barang')
            ->leftJoin('transaksi_details', 'barang.id', '=', 'transaksi_details.barang_id')
            ->select(
                'barang.id', 
                'barang.nama_barang', 
                'barang.kategori', 
                'barang.stok', 
                'barang.harga_jual', 
                'barang.gambar', 
                \Illuminate\Support\Facades\DB::raw('COALESCE(SUM(transaksi_details.qty), 0) as total_terjual')
            )
            ->where('barang.stok', '>', 0)
            ->groupBy('barang.id', 'barang.nama_barang', 'barang.kategori', 'barang.stok', 'barang.harga_jual', 'barang.gambar')
            ->having('total_terjual', '>', 0)
            ->orderBy('total_terjual', 'desc')
            ->get();

        $rekomendasiTerlaris = $semuaBarangLaris->groupBy('kategori')->map(function ($items) {
            return $items->first();
        })->take(8);

        // 2. KUNCI RACIKAN PER HARI (24 Jam / 86400 Detik)
$cacheKey = 'rekomendasi_build_' . date('Y-m-d');

        $rekomendasiData = \Illuminate\Support\Facades\Cache::remember($cacheKey, 60 * 60 * 24, function () {
            $build = [];
            $total = 0;

            // Cari dari Mobo dulu sebagai pondasi utama
            $mobos = Barang::where('kategori', 'Motherboard')->where('stok', '>', 0)->inRandomOrder()->get();
            
            foreach ($mobos as $mobo) {
                
                // 👇 Tentukan ukuran Casing yang BISA menampung Motherboard ini
                $mobo_ff = trim($mobo->form_factor);
                $casing_sizes = [];
                if ($mobo_ff == 'Micro-ATX' || $mobo_ff == 'ITX') {
                    $casing_sizes = ['Micro-ATX', 'ATX', 'E-ATX'];
                } elseif ($mobo_ff == 'ATX') {
                    $casing_sizes = ['ATX', 'E-ATX'];
                } else {
                    $casing_sizes = ['E-ATX'];
                }

                // 1. Pilih Casing (Toleransi data form_factor NULL)
                $casing = Barang::where('kategori', 'Casing')
                                ->where('stok', '>', 0)
                                ->where(function($q) use ($casing_sizes) {
                                    $q->whereIn('form_factor', $casing_sizes)
                                      ->orWhereNull('form_factor')
                                      ->orWhere('form_factor', '');
                                })->inRandomOrder()->first();

                // Jika casing cocok ditemukan, lanjut ke part dalam (internal)
                if ($casing) {
                    
                    // 👇 Batasi ukuran Cooler & VGA maksimal seukuran Casing agar tidak nabrak
                    $casing_ff = trim($casing->form_factor);
                    $allowed_part_sizes = [];
                    if ($casing_ff == 'Micro-ATX') {
                        $allowed_part_sizes = ['Micro-ATX', 'ITX'];
                    } elseif ($casing_ff == 'ATX') {
                        $allowed_part_sizes = ['Micro-ATX', 'ITX', 'ATX'];
                    } else {
                        $allowed_part_sizes = ['Micro-ATX', 'ITX', 'ATX', 'E-ATX'];
                    }

                    // 2. CPU Wajib sesuai Socket Mobo
                    $cpu = Barang::where('kategori', 'Processor')->where('stok', '>', 0)
                                 ->where('socket', 'LIKE', '%' . trim($mobo->socket) . '%')->inRandomOrder()->first();
                                 
                    // 3. RAM Wajib sesuai Tipe Memory Mobo (DDR4 / DDR5)
                    $ram = Barang::where('kategori', 'RAM')->where('stok', '>', 0)
                                 ->where('tipe_memory', 'LIKE', '%' . trim($mobo->tipe_memory) . '%')->inRandomOrder()->first();
                                 
                    // 4. Cooler Wajib sesuai Socket CPU & MUAT di Casing (Toleransi data NULL)
                    $cooler = Barang::where('kategori', 'Cooler')->where('stok', '>', 0)
                                   ->where(function($q) use ($mobo) {
                                       $q->where('socket', 'LIKE', '%' . trim($mobo->socket) . '%')
                                         ->orWhereNull('socket')
                                         ->orWhere('socket', '');
                                   })
                                   ->where(function($q) use ($allowed_part_sizes) {
                                       $q->whereIn('form_factor', $allowed_part_sizes)
                                         ->orWhereNull('form_factor')
                                         ->orWhere('form_factor', '');
                                   })->inRandomOrder()->first();
                                   
                    // 5. VGA Wajib MUAT masuk ke Casing (Toleransi data NULL)
                    $vga = Barang::where('kategori', 'VGA / GPU')->where('stok', '>', 0)
                                 ->where(function($q) use ($allowed_part_sizes) {
                                     $q->whereIn('form_factor', $allowed_part_sizes)
                                       ->orWhereNull('form_factor')
                                       ->orWhere('form_factor', '');
                                 })->inRandomOrder()->first();
                                 
                    // 6. Storage (Bebas NVMe / SATA)
                    $storage = Barang::where('kategori', 'Storage')->where('stok', '>', 0)->inRandomOrder()->first();
                    
                    // Pastikan 5 komponen pendukung ketemu semua sebelum menghitung PSU
                    if ($cpu && $ram && $cooler && $storage && $vga) {
                        
                        // 7. Hitung Daya PSU = Daya CPU + Daya VGA + Overhead 100W
                        $butuhDaya = ($cpu->power_watt ?? 65) + ($vga->power_watt ?? 150) + 100;
                        $psu = Barang::where('kategori', 'PSU')->where('stok', '>', 0)
                                     ->where('power_watt', '>=', $butuhDaya)->orderBy('power_watt', 'asc')->first();
                                     
                        if ($psu) {
                            // Susun array sesuai urutan UI Kustomer
                            $komponenSet = [$cpu, $vga, $mobo, $ram, $storage, $psu, $casing, $cooler];
                            
                            foreach ($komponenSet as $item) {
                                $build[] = $item;
                                $total  += $item->harga_jual;
                            }
                            break; // Hentikan perulangan karena 1 set lengkap & cocok sudah didapat!
                        }
                    }
                }
            }

            return [
                'build' => $build,
                'total' => $total,
            ];
        });

        $rekomendasiBuild = $rekomendasiData['build'];
        $totalHargaBuild  = $rekomendasiData['total'];
    }
    
    return view('customer.katalog', compact(
        'katalog', 'rekomendasiBuild', 'totalHargaBuild', 'rekomendasiTerlaris',
        'komponenList', 'computerList', 'aksesorisList'
    ));
}
   public function show($id)
    {
        $barang = Barang::findOrFail($id);
        
        // 1. Produk Serupa (Kategori Sama - Buat perbandingan harga)
        $produkSerupa = Barang::where('kategori', $barang->kategori)
                            ->where('id', '!=', $id)
                            ->where('stok', '>', 0)
                            ->inRandomOrder()
                            ->take(4)
                            ->get();
        $rekomendasiCocok = collect();

        if ($barang->kategori == 'Processor' && $barang->socket) {
            $rekomendasiCocok = Barang::whereIn('kategori', ['Motherboard', 'Cooler'])
                ->where('stok', '>', 0)
                ->where('socket', 'LIKE', '%' . trim($barang->socket) . '%')
                ->inRandomOrder()->take(4)->get();
                
        } elseif ($barang->kategori == 'Motherboard') {
            $query = Barang::where('stok', '>', 0)->where(function($q) use ($barang) {
                if ($barang->socket) {
                    $q->orWhere(function($q2) use ($barang) {
                        $q2->where('kategori', 'Processor')->where('socket', 'LIKE', '%' . trim($barang->socket) . '%');
                    });
                }
                if ($barang->tipe_memory) {
                    $q->orWhere(function($q2) use ($barang) {
                        $q2->where('kategori', 'RAM')->where('tipe_memory', 'LIKE', '%' . trim($barang->tipe_memory) . '%');
                    });
                }
            });
            $rekomendasiCocok = $query->inRandomOrder()->take(4)->get();
            
        } elseif ($barang->kategori == 'RAM' && $barang->tipe_memory) {
            // Cari Motherboard yang Tipe Memory-nya SAMA
            $rekomendasiCocok = Barang::where('kategori', 'Motherboard')
                ->where('stok', '>', 0)
                ->where('tipe_memory', 'LIKE', '%' . trim($barang->tipe_memory) . '%')
                ->inRandomOrder()->take(4)->get();
        }

        return view('customer.detail', compact('barang', 'produkSerupa', 'rekomendasiCocok'));
    }
}