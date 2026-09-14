<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
  public function index(Request $request)
{
    $kategoris = [
        'Processor', 'Motherboard', 'VGA / GPU', 'RAM', 'Storage', 
        'Casing', 'PSU', 'Cooler', 'Monitor', 'Accessories', 'Computer'
    ];

    if (!$request->has('jenis') && !$request->has('search')) {
        return view('admin.barang.index', [
            'barangs' => null, 
            'kategoris' => $kategoris
        ]);
    }

    $query = Barang::with('components')->latest();
    
    if ($request->has('jenis') && $request->jenis != '') {           
        if ($request->jenis == 'paket_ready') {
            // 🟢 PC READY STOCK: Cuma tampil yang stoknya > 0 (Sudah Dirakit)
            $query->where('jenis', 'paket')
                  ->where('stok', '>', 0);
                  
        } elseif ($request->jenis == 'paket_menu') {
            // 🟣 MASTER RAKITAN: Cuma tampil yang stoknya == 0 (Masih Resep/Blueprint)
            $query->where('jenis', 'paket')
                  ->where('stok', '<=', 0);
                  
        } elseif ($request->jenis == 'komponen') {
            $query->where('jenis', 'komponen');       
        } else {
            $query->where('jenis', $request->jenis);
        }
    }
    
    if ($request->has('kategori') && $request->kategori != '') {
        $query->where('kategori', $request->kategori);
    }
    
    if ($request->has('search') && $request->search != '') {
        $query->where(function($q) use ($request) {
            $q->where('nama_barang', 'like', '%' . $request->search . '%')
              ->orWhere('kode_barang', 'like', '%' . $request->search . '%');
        });
    }
    
    $barangs = $query->paginate(10)->withQueryString(); 
    return view('admin.barang.index', compact('barangs', 'kategoris'));
}

    public function create()
    {
        return view('admin.barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis'       => 'required|in:komponen,paket', 
            'nama_barang' => 'required|string|max:255',
            'kategori'    => 'required',
            'harga_jual'  => 'nullable|numeric',
            'stok'        => 'required|integer',
           'socket'      => 'nullable|string|max:50', 
            'tipe_memory' => 'nullable|string|max:50', 
            'form_factor' => 'nullable|string|max:50', 
            'power_watt'  => 'nullable|integer',       
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);
        
        $prefixes = [
            'Processor' => 'PRC',
            'Motherboard' => 'MOBO',
            'RAM' => 'RAM',
            'Storage (SSD/HDD)' => 'SSD',
            'VGA / GPU' => 'GPU',
            'Casing' => 'CASE',
            'PSU' => 'PSU',
            'Cooler' => 'CPUC',
            'Monitor' => 'MNTR',
            'Keyboard & Mouse' => 'KB',
            'Accessories' => 'ACC',
            'Laptop' => 'LTP',
            'Mini PC' => 'MPC',
            'PC Rakitan / Build' => 'PC'
        ];
        
        $prefix = $prefixes[$request->kategori] ?? 'BRG';
        $existingNumbers = \App\Models\Barang::where('kode_barang', 'LIKE', $prefix . '-%')
            ->pluck('kode_barang')
            ->map(function ($code) use ($prefix) {
                preg_match('/' . $prefix . '-(\d+)/', $code, $matches);
                return isset($matches[1]) ? (int)$matches[1] : 0;
            })
            ->filter()
            ->sort()
            ->values()
            ->toArray();
        $newNumber = 1; 
        foreach ($existingNumbers as $number) {
            if ($number == $newNumber) {
                $newNumber++; 
            } elseif ($number > $newNumber) {
                break; 
            }
        }        
        $formattedNumber = str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        $words = explode(' ', strtoupper(trim($request->nama_barang)));        
        $inisial = '';
        $count = 0;
        foreach ($words as $word) {
            if (preg_match('/[A-Z0-9]/', $word, $matches)) {
                $inisial .= $matches[0];
                $count++;
            }
            if ($count >= 2) break; 
        }
        $lastWord = end($words);
        $lastWord = preg_replace('/[^A-Z0-9]/', '', $lastWord);
        if (strlen($lastWord) > 8) {
            $lastWord = substr($lastWord, 0, 8);
        }
        
        $suffix = $inisial . '-' . $lastWord;
        
        if ($suffix == '-') $suffix = 'ITEM';

        $finalKodeBarang = $prefix . '-' . $formattedNumber . '-' . $suffix;
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('barang_images', 'public');
        }
        
        $barang = \App\Models\Barang::create([
            'jenis'       => $request->jenis,
            'kode_barang' => $finalKodeBarang,
            'nama_barang' => $request->nama_barang,
            'kategori'    => $request->kategori,
            'harga_jual'  => $request->harga_jual ?? 0,
            'stok'        => $request->stok,
            'satuan'      => $request->satuan ?? 'Unit',
            'deskripsi'   => $request->deskripsi,
            'socket'      => $request->socket, // 👇 Masuk ke database
            'tipe_memory' => $request->tipe_memory, // 👇 Masuk ke database
            'form_factor' => $request->form_factor, // 👇 Tambahin ini
            'power_watt'  => $request->power_watt ?? 0,
            'gambar'      => $gambarPath,
        ]);
        
        \App\Models\ActivityLog::record(
            'create', 
            "Menambahkan master barang baru: {$barang->nama_barang} (Stok Awal: {$barang->stok})", 
            $barang
        );

        return redirect()->route('admin.barang.index')->with('success', "Barang berhasil ditambahkan dengan kode: {$finalKodeBarang}!");
    }

    public function edit(string $id)
    {
        $barang = Barang::findOrFail($id);
        return view('admin.barang.edit', compact('barang'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            // Kode barang nullable karena di UI input-nya disabled
            'kode_barang' => 'nullable|unique:barang,kode_barang,'.$id, 
            'nama_barang' => 'required|string|max:255',
            'harga_jual'  => 'required|numeric',
            'socket'      => 'nullable|string|max:50',
            'tipe_memory' => 'nullable|string|max:50',
            'form_factor' => 'nullable|string|max:50', // 👇 Tambahin ini
            'power_watt'  => 'nullable|integer',
            'gambar'      => 'nullable|image|max:2048',
        ]);
        
        $barang = Barang::findOrFail($id);
        $oldValues = $barang->toArray(); 
        
        // $request->except akan otomatis narik data socket & tipe_memory karena namanya sama persis
        $data = $request->except(['gambar']); 

        if ($request->hasFile('gambar')) {
            if ($barang->gambar) {
                Storage::disk('public')->delete($barang->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('barang_images', 'public');
        }
        
        $barang->update($data);
        $newValues = $barang->getChanges();
        
        if (!empty($newValues)) {           
            $actionName = 'update';
            $logMessage = "Memperbarui data barang: {$barang->nama_barang}";
            
            if (isset($newValues['stok'])) {
                $selisih = $newValues['stok'] - $oldValues['stok'];
                
                if ($selisih > 0) {
                    $actionName = 'stock_in'; 
                    $logMessage = "Koreksi penambahan stok manual {$barang->nama_barang} (+{$selisih} unit)";
                } elseif ($selisih < 0) {
                    $actionName = 'stock_out'; 
                    $logMessage = "Koreksi pengurangan stok manual {$barang->nama_barang} (" . $selisih . " unit)";
                }
            }

            ActivityLog::record(
                $actionName, 
                $logMessage, 
                $barang, 
                $oldValues, 
                $newValues
            );
        }

        return redirect()->route('admin.barang.index')->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        try {
            $barang = Barang::findOrFail($id);            
            ActivityLog::record(
                'delete', 
                "Menghapus barang dari sistem: {$barang->nama_barang} ({$barang->kode_barang})", 
                $barang
            );

            if ($barang->gambar) {
                Storage::disk('public')->delete($barang->gambar);
            }
            $barang->delete();

            return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil dihapus!');

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1451) {
                return back()->with('error', 'Barang TIDAK BISA dihapus karena sudah tercatat di Riwayat Transaksi / Invoice!');
            }
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
    public function show($id)
    {
        $barang = \App\Models\Barang::with('components')->findOrFail($id);
        $histories = collect();
        if (class_exists(\App\Models\RakitanLog::class)) {
            $histories = \App\Models\RakitanLog::where('barang_id', $barang->id)
                ->latest()
                ->get();
        }

        return view('admin.barang.show', compact('barang', 'histories'));
    }
}