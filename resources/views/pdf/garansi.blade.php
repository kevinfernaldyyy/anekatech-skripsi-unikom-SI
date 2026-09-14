<!DOCTYPE html>
<html>
<head>
    <title>QC & Garansi - {{ $transaksi->no_transaksi }}</title>
    <style>
        @page { margin: 30px 40px; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #000; line-height: 1.3; }
        .page-break { page-break-after: always; }
        
        table { width: 100%; border-collapse: collapse; border: none; }
        td { vertical-align: top; border: none; padding: 2px 4px; }
        
        .header-title { text-align: right; font-size: 20px; font-weight: bold; text-decoration: underline; padding-top: 10px; }
        .logo { font-size: 28px; font-weight: 900; color: #1e40af; font-style: italic; }
        .logo span { color: #000; }
        
        .box-border { border: 1px solid #000; padding: 5px; margin-bottom: 10px; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 10px; font-size: 10px; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 5px; }
        .data-table th { background-color: #f0f0f0; text-align: center; font-weight: bold; }
        
        /* FIX UNTUK TANDA TANYA (?): Wajib pakai DejaVu Sans agar support simbol ceklis */
        .check-box { 
            display: inline-block; 
            width: 11px; 
            height: 11px; 
            border: 1px solid #000; 
            text-align: center; 
            line-height: 11px; 
            font-size: 10px; 
            font-weight: bold; 
            margin-right: 4px;
            font-family: 'DejaVu Sans', sans-serif; 
        }
        
        .section-title { font-weight: bold; background-color: #e2e8f0; padding: 4px; border: 1px solid #000; text-align: center; margin-top: 10px;}
        .ttd-area { width: 100%; text-align: center; margin-top: 10px; border-top: 1px solid #000; padding-top: 10px;}
        .ttd-box { display: inline-block; width: 45%; text-align: center; height: 80px;}
    </style>
</head>
<body>
    @php
        // RUMUS AMAN: Mengambil data dokumen QC per-unit yang sedang diproses,
        // dengan fallback ke relasi lama jika diperlukan.
        $doc = $dokumen ?? $transaksi->dokumenQc;
    @endphp

    <table>
        <tr>
            <td style="width: 50%;"><div class="logo">DIABLO<span>TECH</span></div></td>
            <td style="width: 50%;" class="header-title">CHECK LIST QC</td>
        </tr>
    </table>

    <table style="margin-top: 15px;">
        <tr>
            <td style="width: 55%;">
                <table>
                    <tr><td style="width: 100px;">Tanggal Pesanan</td><td>: {{ $transaksi->created_at->format('d/m/Y') }}</td></tr>
                    <tr><td>No. Pesanan</td><td>: <strong>{{ $transaksi->no_transaksi }}</strong></td></tr>
                    <tr><td>Nama Customer</td><td>: <strong>{{ $transaksi->nama_customer }}</strong></td></tr>
                    <tr><td>No. HP</td><td>: {{ $transaksi->customer->nomor_telepon ?? '-' }}</td></tr>
                </table>
            </td>
            <td style="width: 45%;">
                <table>
                    <tr><td style="width: 80px;">NO. QC</td><td>: QC-{{ strtoupper(Str::random(6)) }}</td></tr>
                    <tr><td>TGL BUAT</td><td>: {{ optional($doc)->created_at ? $doc->created_at->format('d/m/Y') : date('d/m/Y') }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <table style="margin-top: 5px;">
        <tr>
            <td style="width: 55%;">
                <table>
                    <tr>
                        <td style="width: 100px;">Nama Produk</td>
                        <td>: Custom Build PC @if(isset($doc->nomor_unit)) <strong>(Unit #{{ $doc->nomor_unit }})</strong> @endif</td>
                    </tr>
                    <tr><td>Kode Produk</td><td>: - </td></tr>
                    <tr><td>Jumlah Pesanan</td><td>: 1 Unit</td></tr>
                    <tr><td>Lain-lain</td><td>: - </td></tr>
                </table>
            </td>
            <td style="width: 45%; border: 1px solid #000; padding: 5px;">
                <strong>Jenis Pengambilan :</strong><br>
                <div style="margin-top: 4px;">
                    <!-- LOGIKA CHECKLIST OTOMATIS BERDASARKAN METODE PENGIRIMAN -->
                    <span class="check-box">{{ $transaksi->metode_pengiriman == 'pickup' ? '✓' : '' }}</span> Pengambilan Ditempat<br>
                    <span class="check-box">{{ in_array($transaksi->metode_pengiriman, ['technician', 'courier', 'delivery']) ? '✓' : '' }}</span> Diantar<br>
                    <div style="margin-left: 15px; margin-top: 3px;">
                        <span class="check-box"></span> Tokped &nbsp;&nbsp;
                        <span class="check-box"></span> Shopee &nbsp;&nbsp;
                        <span class="check-box">{{ $transaksi->metode_pengiriman == 'technician' ? '✓' : '' }}</span> Kurir Internal
                    </div>
                </div>
            </td>
        </tr>
    </table>
@php
        $doc = $dokumen ?? $transaksi->dokumenQc;
        $targetUnit = $doc->nomor_unit ?? 1;

        // 1. FILTER BARANG KHUSUS UNTUK UNIT YANG SEDANG DICETAK (100% AKURAT)
        $itemUnitIni = $transaksi->details->filter(function($detail) use ($targetUnit) {
            return ($detail->nomor_unit ?? 1) == $targetUnit;
        });

        // 2. HITUNG SUBTOTAL AKURAT KHUSUS PC UNIT INI
        $subtotalUnitIni = $itemUnitIni->sum('subtotal');
    @endphp
   <div class="section-title">
    Spesifikasi & Daftar Komponen Tergaransi (UNIT #{{ $targetUnit }})
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 55%;">Nama Barang</th>
                <th style="width: 20%;">Harga Satuan</th>
                <th style="width: 5%;">QTY</th>
                <th style="width: 15%;">Total</th>
            </tr>
        </thead>
<tbody>
    @forelse($itemUnitIni as $index => $detail)
    <tr>
        <td style="text-align: center;">{{ $loop->iteration }}</td>
        <td>
            <strong>{{ $detail->barang->nama_barang ?? $detail->nama_barang_custom }}</strong>
            @if(optional($detail->barang)->kode_barang)
                <br><span style="font-size: 8.5px; color: #555;">Kode: {{ $detail->barang->kode_barang }}</span>
            @endif
        </td>
        <td style="text-align: right;">Rp {{ number_format($detail->harga_satuan ?? 0, 0, ',', '.') }}</td>
        <td style="text-align: center;">{{ $detail->qty }}</td>
        <td style="text-align: right;">Rp {{ number_format($detail->subtotal ?? 0, 0, ',', '.') }}</td>
    </tr>
    @empty
    <tr>
        <td colspan="5" style="text-align: center; color: #888; font-style: italic; padding: 10px;">
            Tidak ada rincian komponen untuk Unit #{{ $targetUnit }}.
        </td>
    </tr>
    @endforelse
</tbody>
    </table>

    <!-- TOTAL KHUSUS UNIT INI -->
    <table style="margin-bottom: 10px;">
        <tr>
            <td style="width: 65%;"></td>
            <td style="width: 35%;">
                <table style="font-weight: bold; border: 1px solid #000; padding: 2px;">
                    <tr>
                       <td>Sub Total (Unit #{{ $targetUnit }})</td>
                        <td style="text-align: right;">Rp {{ number_format($subtotalUnitIni, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="section-title">List Checking Unit</div>
    <div class="box-border">
        <table>
            <tr>
                <td style="width: 35%; border-right: 1px solid #000;">
                    <span style="font-size: 9px;">✓ = Aman &nbsp;&nbsp; X = Tidak Aman</span><br><br>
                    <span class="check-box">✓</span> P1. Garansi<br>
                    <span class="check-box">✓</span> P2. Kondisi Barang<br>
                    <span class="check-box">✓</span> P3. Kestabilan Parts<br>
                </td>
                <td style="width: 65%; padding-left: 10px;">
                    <table>
                        <tr>
                            <td style="width: 50%;">
                                <span class="check-box">✓</span> Install Hardware<br>
                                <span class="check-box">✓</span> Install Software<br>
                                <span class="check-box">✓</span> Install Software Customer<br>
                                <span class="check-box">✓</span> Install Games Customer
                            </td>
                            <td style="width: 50%;">
                                <span class="check-box">✓</span> Pengecekan Slot (FP & BP)<br>
                                <span class="check-box">✓</span> Penyegelan Unit<br>
                                <span class="check-box">✓</span> Durability Test<br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <table class="box-border" style="width: 100%;">
        <tr><td style="width: 120px;">Alamat Pengiriman</td><td>: {{ $transaksi->customer->alamat ?? 'Ambil Sendiri' }}</td></tr>
        <tr><td>Ongkos Kirim</td><td>: Rp {{ number_format($transaksi->biaya_pengiriman ?? 0, 0, ',', '.') }}</td></tr>
        <tr><td>Catatan / Note</td><td>: {{ optional($doc)->catatan_teknisi ?? 'Semua Oke' }}</td></tr>
    </table>

    <div class="ttd-area">
        <table>
            <tr>
                <td class="ttd-box">
                    Dibuat Oleh,<br><br><br><br>
                    <strong>( {{ $transaksi->teknisi->name ?? 'Teknisi' }} )</strong>
                </td>
                <td class="ttd-box">
                    Mengetahui,<br><br><br><br>
                    <strong>( Our Director )</strong>
                </td>
            </tr>
        </table>
    </div>

    <div class="page-break"></div>

    <div style="text-align: center; margin-bottom: 20px;">
        <div class="logo">DIABLO<span>TECH</span></div>
        <div style="font-size: 18px; font-weight: bold; margin-top: 5px; text-decoration: underline;">KARTU GARANSI</div>
    </div>

    <div style="font-size: 11px; text-align: justify;">
        <strong>Syarat-syarat Garansi :</strong>
        <ol style="margin-top: 5px;">
            <li>Garansi berlaku jika stiker garansi yang ditempel diatas produk masih utuh, tidak rusak atau robek.</li>
            <li>Garansi tidak berlaku untuk kerusakan yang diakibatkan oleh:
                <ul style="margin-top: 5px;">
                    <li>Kesalahan perawatan yang dilakukan oleh pelanggan. Modifikasi dan penggunaan yang tidak tepat baik karena permintaan sendiri atau pihak ketiga.</li>
                    <li>Bencana alam dan kerusakan akibat factor luar, seperti cairan, korosi (kecuali motherboard yang ada fitur Anti Korosi), serangga dan lain-lain.</li>
                    <li>Cacat fisik dan terbakarnya komponen.</li>
                    <li>Penginstalan, penyimpanan dan pemakaian sumber listrik yang tidak sesuai.</li>
                    <li>Kerusakan yang dikarenakan ketidakcocokan penggunaan software dan hardware, baik untuk penggunaan sekarang ataupun yang akan datang.</li>
                </ul>
            </li>
        </ol>

        <strong>Ketentuan-ketentuan lain:</strong>
        <ol style="margin-top: 5px;">
            <li>Garansi selama 1 tahun pertama meliputi suku cadang dan ongkos kerja. Garansi tahun berikutnya hanya atas ongkos kerja (Sesuai ketentuan).</li>
            <li>Diablo Tech berhak untuk menggantikan produk yang rusak dengan jenis produk yang setara, atau yang lebih tinggi spesifikasinya dengan penambahan biaya, bila produk yang rusak tersebut sudah tidak diproduksi lagi oleh pabrik.</li>
            <li>Diablo Tech berhak menolak tuntutan garansi yang tidak sesuai dengan ketentuan yang berlaku.</li>
        </ol>
    </div>

    <div style="border-top: 1px dashed #666; margin: 40px 0; text-align: center;">
        <span style="background: #fff; padding: 0 10px; position: relative; top: -8px; color: #666; font-size: 10px;">✂ Gunting disini (Dikembalikan ke toko) ✂</span>
    </div>

    <table style="border: 1px solid #000; width: 100%;">
        <tr>
            <td style="width: 50%; border-right: 1px solid #000; padding: 15px;">
                <div class="logo" style="font-size: 18px; margin-bottom: 10px;">DIABLO<span>TECH</span></div>
                <table>
                    <tr><td style="width: 100px; padding-bottom: 5px;">No. Transaksi</td><td>: {{ $transaksi->no_transaksi }}</td></tr>
                    <tr>
                        <td style="padding-bottom: 5px;">Jenis Barang</td>
                        <td>: Custom Build PC @if(isset($doc->nomor_unit)) (Unit #{{ $doc->nomor_unit }}) @endif</td>
                    </tr>
                    <tr><td style="padding-bottom: 5px;">Model</td><td>: Custom Rakitan</td></tr>
                    <tr><td style="padding-bottom: 5px;">Tanggal Pembelian</td><td>: {{ $transaksi->created_at->format('d/m/Y') }}</td></tr>
                    <tr><td style="padding-bottom: 5px; height: 50px;">Stempel Toko</td><td>: </td></tr>
                </table>
            </td>
            <td style="width: 50%; padding: 15px;">
                <table style="margin-top: 30px;">
                    <tr><td style="width: 100px; padding-bottom: 5px;">Nama Pembeli</td><td>: {{ $transaksi->nama_customer }}</td></tr>
                    <tr><td style="padding-bottom: 5px;">Nama Perusahaan</td><td>: -</td></tr>
                    <tr><td style="padding-bottom: 5px;">Alamat</td><td>: {{ $transaksi->customer->alamat ?? '-' }}</td></tr>
                    <tr><td style="padding-bottom: 5px;">E-Mail</td><td>: {{ $transaksi->customer->email ?? '-' }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Tampilkan Lampiran Foto QC Dinamis per Unit -->
    @if(optional($doc)->foto_depan)
        <div class="page-break"></div>

        <div style="text-align: center; margin-bottom: 20px;">
            <div class="logo">DIABLO<span>TECH</span></div>
            <div style="font-size: 16px; font-weight: bold; margin-top: 5px;">LAMPIRAN: BUKTI VISUAL QUALITY CONTROL</div>
            @if(isset($doc->nomor_unit))
                <div style="font-size: 13px; font-weight: bold; margin-top: 3px; color: #1e40af;">UNIT #{{ $doc->nomor_unit }}</div>
            @endif
        </div>

        <table style="width: 100%; text-align: center; margin-top: 30px;">
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