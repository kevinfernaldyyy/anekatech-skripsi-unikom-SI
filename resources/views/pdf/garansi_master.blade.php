<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Garansi Resmi - Unit #{{ $nomor_unit ?? '1' }}</title>
    <style>
        @page { margin: 30px 40px; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 11px; 
            color: #000; 
            line-height: 1.3; 
            margin: 0;
            padding: 20px;
        }
        .page-break { page-break-after: always; }
        .logo { font-size: 26px; font-weight: 900; color: #1e40af; font-style: italic; }
        .logo span { color: #000; }
        
        /* TABEL DAFTAR KOMPONEN GARANSI */
        .item-table { width: 100%; border-collapse: collapse; margin: 10px 0 15px 0; font-size: 10px; }
        .item-table th, .item-table td { border: 1px solid #000; padding: 6px 8px; }
        .item-table th { background-color: #f0f0f0; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 9px; }
        
        /* TOMBOL ACTION WEB (HILANG SAAT DIPRINT) */
        @media print {
            .no-print { display: none !important; }
            body { padding: 0 !important; }
        }
        .no-print {
            max-width: 800px;
            margin: 0 auto 20px auto;
            display: flex;
            justify-content: space-between;
            background: #f8fafc;
            padding: 10px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        .btn {
            padding: 8px 16px;
            font-size: 12px;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn-print { background: #1e40af; color: white; }
        .btn-close { background: #e2e8f0; color: #333; }
    </style>
</head>
<body>
    @php
        $targetUnit = $nomor_unit ?? ($log->nomor_unit ?? 1);
        $komponens = $log->barang->components ?? collect();
    @endphp

    <!-- ================= HALAMAN 1: KARTU GARANSI & DAFTAR PART ================= -->
   <div style="text-align: center; margin-bottom: 15px;">
        <div class="logo">DIABLO<span>TECH</span></div>
        <div style="font-size: 16px; font-weight: bold; margin-top: 5px; text-decoration: underline; text-transform: uppercase;">
            KARTU GARANSI RESMI : {{ $log->barang->nama_barang ?? 'PC RAKITAN' }}
        </div>
        <div style="font-size: 11px; font-weight: bold; color: #1e40af; margin-top: 2px;">
            (UNIT #{{ $targetUnit }})
        </div>
        <div style="font-size: 10px; color: #444; margin-top: 4px;">
            No. Batch QC: <strong>QC-{{ date('Ymd') }}-{{ str_pad($log->id ?? '1', 3, '0', STR_PAD_LEFT) }}-{{ $targetUnit }}</strong> | Tanggal Perakitan: {{ ($log->created_at ?? now())->format('d/m/Y') }}
        </div>
    </div>

    <!-- SPESIFIKASI & KOMPONEN YANG DILINDUNGI GARANSI -->
    <div style="font-size: 11px; font-weight: bold; margin-top: 10px;">
        Spesifikasi & Daftar Komponen Tergaransi (UNIT #{{ $targetUnit }}) :
    </div>
    <table class="item-table">
        <thead>
            <tr>
                <th style="width: 8%; text-align: center;">No</th>
                <th style="width: 75%;">Nama Komponen / Deskripsi</th>
                <th style="width: 17%; text-align: center;">QTY</th>
            </tr>
        </thead>
<tbody>
    @php 
        $no = 1; 
        $komponens = optional(optional($log)->barang)->components 
                     ?? optional(optional($log)->barang)->komponen 
                     ?? optional($paket)->components 
                     ?? optional($paket)->komponen 
                     ?? collect();
    @endphp

    @if($komponens->count() > 0)
        @foreach($komponens as $komp)
        @php
            // 🔥 SAPU JAGAT NAMA KOMPONEN (Pasti Kena Salah Satunya!)
            $namaKomponen = $komp->nama_barang 
                         ?? $komp->nama_komponen 
                         ?? $komp->nama 
                         ?? optional($komp->barang)->nama_barang 
                         ?? optional($komp->komponen)->nama_barang 
                         ?? optional($komp->component)->nama_barang 
                         ?? optional($komp->part)->nama_barang 
                         ?? optional($komp->item)->nama_barang 
                         ?? optional($komp->produk)->nama_barang 
                         ?? optional($komp->detail)->nama_barang 
                         ?? 'Komponen PC';

            // 🔥 SAPU JAGAT KODE / SERIAL NUMBER
            $kodeKomponen = $komp->kode_barang 
                         ?? $komp->kode_komponen 
                         ?? $komp->sn 
                         ?? optional($komp->barang)->kode_barang 
                         ?? optional($komp->komponen)->kode_barang 
                         ?? optional($komp->component)->kode_barang 
                         ?? optional($komp->part)->kode_barang 
                         ?? optional($komp->item)->kode_barang 
                         ?? optional($komp->produk)->kode_barang 
                         ?? '-';

            // 🔥 SAPU JAGAT QUANTITY
            $qtyKomponen  = $komp->qty ?? $komp->jumlah ?? $komp->quantity ?? 1;
        @endphp
        <tr>
            <td align="center">{{ $no++ }}</td>
            <td>
                <strong>{{ $namaKomponen }}</strong><br>
                <span style="font-size: 10px; color: #666;">Kode / SN: {{ $kodeKomponen }}</span>
            </td>
            <td align="center">{{ $qtyKomponen }}x</td>
        </tr>
        @endforeach

    @elseif(isset($transaksi) && $transaksi->details)
        @foreach($transaksi->details as $item)
        <tr>
            <td align="center">{{ $no++ }}</td>
            <td>
                <strong>{{ $item->barang->nama_barang ?? $item->nama_barang_custom }}</strong><br>
                <span style="font-size: 10px; color: #666;">Kode: {{ $item->barang->kode_barang ?? '-' }}</span>
            </td>
            <td align="center">{{ $item->qty }}x</td>
        </tr>
        @endforeach
    @endif
</tbody>
    </table>

    <!-- SYARAT & KETENTUAN GARANSI -->
    <div style="font-size: 11px; text-align: justify;">
        <strong>Syarat-syarat Garansi :</strong>
        <ol style="margin-top: 5px; padding-left: 18px;">
            <li>Garansi berlaku jika stiker garansi yang ditempel diatas produk masih utuh, tidak rusak atau robek.</li>
            <li>Garansi tidak berlaku untuk kerusakan yang diakibatkan oleh:
                <ul style="margin-top: 4px; padding-left: 15px;">
                    <li>Kesalahan perawatan yang dilakukan oleh pelanggan. Modifikasi dan penggunaan yang tidak tepat baik karena permintaan sendiri atau pihak ketiga.</li>
                    <li>Bencana alam dan kerusakan akibat faktor luar, seperti cairan, korosi (kecuali motherboard yang ada fitur Anti Korosi), serangga dan lain-lain.</li>
                    <li>Cacat fisik dan terbakarnya komponen.</li>
                    <li>Penginstalan, penyimpanan dan pemakaian sumber listrik yang tidak sesuai.</li>
                    <li>Kerusakan yang dikarenakan ketidakcocokan penggunaan software dan hardware, baik untuk penggunaan sekarang ataupun yang akan datang.</li>
                </ul>
            </li>
        </ol>

        <strong>Ketentuan-ketentuan lain:</strong>
        <ol style="margin-top: 5px; padding-left: 18px;">
            <li>Garansi selama 1 tahun pertama meliputi suku cadang dan ongkos kerja. Garansi tahun berikutnya hanya atas ongkos kerja (Sesuai ketentuan).</li>
            <li>Diablo Tech berhak untuk menggantikan produk yang rusak dengan jenis produk yang setara, atau yang lebih tinggi spesifikasinya dengan penambahan biaya, bila produk yang rusak tersebut sudah tidak diproduksi lagi oleh pabrik.</li>
            <li>Diablo Tech berhak menolak tuntutan garansi yang tidak sesuai dengan ketentuan yang berlaku.</li>
        </ol>
    </div>

    <!-- POTONGAN KEMBALI KE TOKO -->
    <div style="border-top: 1px dashed #666; margin: 25px 0; text-align: center;">
        <span style="background: #fff; padding: 0 10px; position: relative; top: -8px; color: #666; font-size: 10px;">✂ Gunting disini (Dikembalikan ke toko) ✂</span>
    </div>

    <table style="border: 1px solid #000; width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 50%; border-right: 1px solid #000; padding: 10px; vertical-align: top;">
                <div class="logo" style="font-size: 16px; margin-bottom: 6px;">DIABLO<span>TECH</span></div>
                <table style="width: 100%; font-size: 10px;">
                    <tr><td style="width: 95px; padding-bottom: 3px;">No. Batch QC</td><td>: <strong>Batch #{{ $log->id ?? '1' }}</strong></td></tr>
                    <tr>
                        <td style="padding-bottom: 3px;">Jenis Barang</td>
                        <td>: Custom Build PC <strong>(Unit #{{ $targetUnit }})</strong></td>
                    </tr>
                    <tr><td style="padding-bottom: 3px;">Model / Spesifikasi</td><td>: {{ $log->barang->nama_barang ?? 'Custom Rakitan' }}</td></tr>
                    <tr><td style="padding-bottom: 3px;">Tgl Produksi</td><td>: {{ ($log->created_at ?? now())->format('d/m/Y') }}</td></tr>
                    <tr><td style="padding-bottom: 3px; height: 35px;">Stempel Toko</td><td>: </td></tr>
                </table>
            </td>
            <td style="width: 50%; padding: 10px; vertical-align: top;">
                <table style="margin-top: 20px; width: 100%; font-size: 10px;">
                    <tr><td style="width: 95px; padding-bottom: 3px;">Nama Pembeli</td><td>: <strong>.........................................</strong></td></tr>
                    <tr><td style="padding-bottom: 3px;">Perusahaan</td><td>: -</td></tr>
                    <tr><td style="padding-bottom: 3px;">Alamat</td><td>: ................................................................</td></tr>
                    <tr><td style="padding-bottom: 3px;">No. Telp / WA</td><td>: ................................................................</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- ================= HALAMAN 2: LAMPIRAN FOTO PC PER-UNIT (JIKA ADA) ================= -->
    @if(optional($log)->foto_depan || optional($log)->foto_belakang || optional($log)->foto_menyala)
        <div class="page-break"></div>

        <div style="text-align: center; margin-bottom: 20px;">
            <div class="logo">DIABLO<span>TECH</span></div>
            <div style="font-size: 16px; font-weight: bold; margin-top: 5px;">LAMPIRAN: BUKTI VISUAL QUALITY CONTROL</div>
            <div style="font-size: 13px; font-weight: bold; margin-top: 3px; color: #1e40af;">UNIT #{{ $targetUnit }}</div>
        </div>

        <table style="width: 100%; text-align: center; margin-top: 25px; border-collapse: collapse;">
            <tr>
                @if($log->foto_depan)
                <td style="padding: 10px;">
                    <div style="border: 1px solid #000; padding: 5px; display: inline-block;">
                        <div style="font-weight: bold; margin-bottom: 5px; text-transform: uppercase;">1. Tampak Depan PC</div>
                        <img src="{{ public_path('storage/' . $log->foto_depan) }}" style="width: 200px; height: 200px; object-fit: cover;">
                    </div>
                </td>
                @endif

                @if($log->foto_belakang)
                <td style="padding: 10px;">
                    <div style="border: 1px solid #000; padding: 5px; display: inline-block;">
                        <div style="font-weight: bold; margin-bottom: 5px; text-transform: uppercase;">2. Cable Management</div>
                        <img src="{{ public_path('storage/' . $log->foto_belakang) }}" style="width: 200px; height: 200px; object-fit: cover;">
                    </div>
                </td>
                @endif
            </tr>
            @if($log->foto_menyala)
            <tr>
                <td colspan="2" style="padding: 20px;">
                    <div style="border: 1px solid #000; padding: 5px; display: inline-block;">
                        <div style="font-weight: bold; margin-bottom: 5px; text-transform: uppercase;">3. Test Menyala / Durability</div>
                        <img src="{{ public_path('storage/' . $log->foto_menyala) }}" style="width: 300px; height: 200px; object-fit: cover;">
                    </div>
                </td>
            </tr>
            @endif
        </table>
    @endif

</body>
</html>