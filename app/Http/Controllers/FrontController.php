<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Cache;

class FrontController extends Controller
{
   public function index(Request $request)
{
    $query = Barang::where('stok', '>', 0);
    if ($request->filled('search')) {
        $query->where('nama_barang', 'like', '%' . $request->search . '%');
    }
    if ($request->filled('kategori')) {
        $query->where('kategori', $request->kategori); 
    }
    $katalog = $query->latest()->take(5)->get();
    $cacheKey = 'daily_pc_build_' . date('Y-m-d');
    $dailyBuild = \Illuminate\Support\Facades\Cache::remember($cacheKey, 86400, function () {
        return $this->generateDailyBuild();
    });

    return view('welcome', compact('katalog', 'dailyBuild'));
}

    private function generateDailyBuild()
    {
        // 1. Cari Motherboard acak yang ada stoknya sebagai patokan soket & RAM
        $mobo = Barang::where('kategori', 'like', '%Motherboard%')->where('stok', '>', 0)->inRandomOrder()->first()
             ?? Barang::where('stok', '>', 0)->first();

        // 2. Cari Processor yang soketnya cocok sama Motherboard
        $cpu = Barang::where('kategori', 'like', '%Processor%')
                     ->when($mobo && $mobo->socket, fn($q) => $q->where('socket', $mobo->socket))
                     ->where('stok', '>', 0)->inRandomOrder()->first()
            ?? Barang::where('kategori', 'like', '%Processor%')->where('stok', '>', 0)->first();

        // 3. Cari RAM yang tipenya cocok sama Motherboard
        $ram = Barang::where('kategori', 'like', '%RAM%')
                     ->when($mobo && $mobo->tipe_memory, fn($q) => $q->where('tipe_memory', $mobo->tipe_memory))
                     ->where('stok', '>', 0)->inRandomOrder()->first()
            ?? Barang::where('kategori', 'like', '%RAM%')->where('stok', '>', 0)->first();

        // 4. Cari komponen pelengkap (VGA, Casing, PSU)
        $gpu  = Barang::where('kategori', 'like', '%VGA%')->where('stok', '>', 0)->inRandomOrder()->first();
        $case = Barang::where('kategori', 'like', '%Casing%')->where('stok', '>', 0)->inRandomOrder()->first();
        $psu  = Barang::where('kategori', 'like', '%PSU%')->where('stok', '>', 0)->inRandomOrder()->first();

        // Kumpulkan menjadi 6 komponen utama rakitan
        $items = collect([
            'Motherboard' => $mobo,
            'Processor'   => $cpu,
            'RAM'         => $ram,
            'VGA / GPU'   => $gpu,
            'Casing'      => $case,
            'PSU'         => $psu,
        ])->filter(); // Buang yang kosong jika stok gudang terbatas

        // Hitung estimasi total harga 1 set
        $totalHarga = $items->sum(function ($item) {
            return $item->harga_jual ?? $item->harga ?? $item->harga_barang ?? 0;
        });

        return (object) [
            'items'       => $items,
            'total_harga' => $totalHarga,
        ];
    }
}