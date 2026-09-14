<!DOCTYPE html>
<html>
<head>
    <title>Kartu Garansi - {{ $transaksi->no_transaksi }}</title>
    <style>
        @page { margin: 30px 40px; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #000; line-height: 1.3; }
        .page-break { page-break-after: always; }
        .logo { font-size: 26px; font-weight: 900; color: #1e40af; font-style: italic; }
        .logo span { color: #000; }
        
        /* TABEL DAFTAR KOMPONEN GARANSI */
        .item-table { width: 100%; border-collapse: collapse; margin: 10px 0 15px 0; font-size: 10px; }
        .item-table th, .item-table td { border: 1px solid #000; padding: 5px 8px; }
        .item-table th { background-color: #f0f0f0; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 9px; }
    </style>
</head>
<body>
@php
        $doc = $dokumen ?? $transaksi->dokumenQc;
        
        // 👇 PASTIKAN DIA MEMBACA $nomorUnit DARI CONTROLLER DULU 👇
        $targetUnit = $nomorUnit ?? ($doc->nomor_unit ?? 1);

        // FILTER BARANG KHUSUS UNTUK UNIT YANG SEDANG DICETAK (100% AKURAT)
        $itemUnitIni = $transaksi->details->filter(function($detail) use ($targetUnit) {
            return ($detail->nomor_unit ?? 1) == $targetUnit;
        });
    @endphp

    <!-- ================= HALAMAN 1: KARTU GARANSI & DAFTAR PART ================= -->
    <div style="text-align: center; margin-bottom: 15px;">
        <div class="logo">DIABLO<span>TECH</span></div>
        <div style="font-size: 18px; font-weight: bold; margin-top: 5px; text-decoration: underline;">
            KARTU GARANSI RESMI
            @if(isset($doc->nomor_unit)) (UNIT #{{ $doc->nomor_unit }}) @endif
        </div>
        <div style="font-size: 10px; color: #444; margin-top: 3px;">
            No. Transaksi: <strong>{{ $transaksi->no_transaksi }}</strong> | Tanggal Pembelian: {{ $transaksi->created_at->format('d/m/Y') }}
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
            {{-- 👇 HANYA TAMPILKAN BARANG MILIK UNIT INI 👇 --}}
            @forelse($itemUnitIni as $index => $detail)
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td>
                    <strong>{{ $detail->barang->nama_barang ?? $detail->nama_barang_custom }}</strong>
                    @if(optional($detail->barang)->kode_barang)
                        <br><span style="font-size: 8.5px; color: #555;">Kode: {{ $detail->barang->kode_barang }}</span>
                    @endif
                </td>
                <td style="text-align: center; font-weight: bold;">{{ $detail->qty }}x</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align: center; color: #888; font-style: italic; padding: 10px;">
                    Tidak ada rincian komponen untuk Unit #{{ $targetUnit }}.
                </td>
            </tr>
            @endforelse
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
                    <li>Bencana alam dan kerusakan akibat factor luar, seperti cairan, korosi (kecuali motherboard yang ada fitur Anti Korosi), serangga dan lain-lain.</li>
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

    <table style="border: 1px solid #000; width: 100%;">
        <tr>
            <td style="width: 50%; border-right: 1px solid #000; padding: 10px;">
                <div class="logo" style="font-size: 16px; margin-bottom: 6px;">DIABLO<span>TECH</span></div>
                <table>
                    <tr><td style="width: 95px; padding-bottom: 3px;">No. Transaksi</td><td>: <strong>{{ $transaksi->no_transaksi }}</strong></td></tr>
                    <tr>
                        <td style="padding-bottom: 3px;">Jenis Barang</td>
                        <td>: Custom Build PC @if(isset($doc->nomor_unit)) <strong>(Unit #{{ $doc->nomor_unit }})</strong> @endif</td>
                    </tr>
                    <tr><td style="padding-bottom: 3px;">Model</td><td>: Custom Rakitan</td></tr>
                    <tr><td style="padding-bottom: 3px;">Tgl Pembelian</td><td>: {{ $transaksi->created_at->format('d/m/Y') }}</td></tr>
                    <tr><td style="padding-bottom: 3px; height: 35px;">Stempel Toko</td><td>: </td></tr>
                </table>
            </td>
            <td style="width: 50%; padding: 10px;">
                <table style="margin-top: 20px;">
                    <tr><td style="width: 95px; padding-bottom: 3px;">Nama Pembeli</td><td>: <strong>{{ $transaksi->nama_customer }}</strong></td></tr>
                    <tr><td style="padding-bottom: 3px;">Perusahaan</td><td>: -</td></tr>
                    <tr><td style="padding-bottom: 3px;">Alamat</td><td>: {{ $transaksi->customer->alamat ?? '-' }}</td></tr>
                    <tr><td style="padding-bottom: 3px;">E-Mail</td><td>: {{ $transaksi->customer->email ?? '-' }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- ================= HALAMAN 2: LAMPIRAN FOTO PC PER-UNIT ================= -->
    @if(optional($doc)->foto_depan)
        <div class="page-break"></div>

        <div style="text-align: center; margin-bottom: 20px;">
            <div class="logo">DIABLO<span>TECH</span></div>
            <div style="font-size: 16px; font-weight: bold; margin-top: 5px;">LAMPIRAN: BUKTI VISUAL QUALITY CONTROL</div>
            @if(isset($doc->nomor_unit))
                <div style="font-size: 13px; font-weight: bold; margin-top: 3px; color: #1e40af;">UNIT #{{ $doc->nomor_unit }}</div>
            @endif
        </div>

        <table style="width: 100%; text-align: center; margin-top: 25px;">
            <tr>
                <td style="padding: 10px;">
                    <div style="border: 1px solid #000; padding: 5px; display: inline-block;">
                        <div style="font-weight: bold; margin-bottom: 5px; text-transform: uppercase;">1. Tampak Depan PC</div>
                        <img src="{{ public_path('storage/' . $doc->foto_depan) }}" style="width: 200px; height: 200px; object-fit: cover;">
                    </div>
                </td>
                <td style="padding: 10px;">
                    <div style="border: 1px solid #000; padding: 5px; display: inline-block;">
                        <div style="font-weight: bold; margin-bottom: 5px; text-transform: uppercase;">2. Cable Management</div>
                        <img src="{{ public_path('storage/' . $doc->foto_belakang) }}" style="width: 200px; height: 200px; object-fit: cover;">
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 20px;">
                    <div style="border: 1px solid #000; padding: 5px; display: inline-block;">
                        <div style="font-weight: bold; margin-bottom: 5px; text-transform: uppercase;">3. Test Menyala / Durability</div>
                        <img src="{{ public_path('storage/' . $doc->foto_menyala) }}" style="width: 300px; height: 200px; object-fit: cover;">
                    </div>
                </td>
            </tr>
        </table>
    @endif
</body>
</html>