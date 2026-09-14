<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offering Letter - {{ $transaksi->no_transaksi }}</title>
    <style>
        /* Base Setup */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            color: #333; 
            line-height: 1.6; 
            margin: 0; 
            padding: 0; 
            background-color: #fff;
        }
        .container { padding: 40px; max-width: 900px; margin: auto; }

        /* Header Section */
        .header-wrapper { 
            display: flex; 
            justify-content: space-between; 
            align-items: flex-start; 
            border-bottom: 2px solid #2c3e50; 
            padding-bottom: 20px; 
            margin-bottom: 30px; 
        }
        .brand-logo { width: 120px; margin-bottom: 10px; }
        .company-info h1 { 
            margin: 0; 
            color: #2c3e50; 
            font-size: 22px; 
            letter-spacing: 1px;
        }
        .company-info p { margin: 2px 0; font-size: 12px; color: #7f8c8d; }

        /* Document Title */
        .doc-title { 
            text-align: right; 
        }
        .doc-title h2 { 
            margin: 0; 
            color: #2980b9; 
            font-size: 28px; 
            text-transform: uppercase; 
        }
        .doc-title p { margin: 0; font-size: 14px; font-weight: bold; }

        /* Meta Section (Customer vs Info) */
        .meta-container { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 50px; 
            margin-bottom: 30px; 
        }
        .meta-box h3 { 
            font-size: 14px; 
            text-transform: uppercase; 
            color: #7f8c8d; 
            border-bottom: 1px solid #eee; 
            padding-bottom: 5px; 
            margin-bottom: 10px;
        }
        .meta-box p { margin: 3px 0; font-size: 14px; }

        /* Table Section */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        thead th { 
            background-color: #2c3e50; 
            color: #fff; 
            text-align: left; 
            padding: 12px 10px; 
            font-size: 13px;
            text-transform: uppercase;
        }
        tbody td { 
            padding: 12px 10px; 
            border-bottom: 1px solid #eee; 
            font-size: 13px; 
            vertical-align: top;
        }
        .item-spec { font-size: 11px; color: #666; margin-top: 5px; list-style-type: square; }
        
        /* Summary Section */
        .summary-wrapper { margin-top: 20px; display: flex; justify-content: flex-end; }
        .summary-table { width: 300px; }
        .summary-table td { padding: 8px 10px; border: none; }
        .summary-table .label { text-align: right; color: #7f8c8d; }
        .summary-table .value { text-align: right; font-weight: bold; width: 150px; }
        .total-row { background-color: #f8f9fa; font-size: 16px; color: #2980b9; }

        /* Footer & Signature */
        .terms { margin-top: 40px; font-size: 11px; color: #7f8c8d; border-left: 3px solid #2980b9; padding-left: 15px; }
        .signature-wrapper { 
            margin-top: 60px; 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            text-align: center; 
        }
        .sign-space { height: 80px; }
        .sign-name { font-weight: bold; text-decoration: underline; margin-bottom: 0; }
        
        /* Print Setup */
        @media print {
            body { background: none; }
            .container { padding: 0; width: 100%; }
            @page { margin: 1.5cm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="container">
    <div class="header-wrapper">
        <div class="company-info">
            <img src="{{ asset('img/logo.jpeg') }}" alt="Logo" class="brand-logo">
            <h1>PT. ANEKA TEKNOLOGI SOLUSINDO</h1>
            <p>Jl. Teknologi No. 123, Bandung, Jawa Barat</p>
            <p>Telp: (022) 1234567 | sales@anekatech.com</p>
        </div>
        <div class="doc-title">
            <h2>Quotation</h2>
            <p>Ref: {{ $transaksi->no_transaksi }}</p>
            <p style="font-weight: normal; color: #7f8c8d;">Tanggal: {{ date('d F Y') }}</p>
        </div>
    </div>

    <div class="meta-container">
        <div class="meta-box">
            <h3>Kepada Yth:</h3>
            <p><strong>{{ $transaksi->nama_customer }}</strong></p>
            <p>{{ $transaksi->customer->perusahaan ?? 'Personal' }}</p>
            <p>{{ $transaksi->alamat ?? '-' }}</p>
        </div>
        <div class="meta-box" style="text-align: right;">
            <h3>Detail Pengiriman & Pembayaran:</h3>
            
            <!-- 👇 Logika Pengecekan Metode Pengiriman 👇 -->
            <p><strong>Metode Pengiriman:</strong> 
                @if($transaksi->metode_pengiriman == 'pickup')
                    Ambil di Toko (Self-Pickup)
                @elseif($transaksi->metode_pengiriman == 'courier')
                    Kurir Ekspedisi Reguler
                @elseif($transaksi->metode_pengiriman == 'technician')
                    Teknisi Datang ke Lokasi
                @else
                    {{ ucfirst($transaksi->metode_pengiriman ?? 'Belum Ditentukan') }}
                @endif
            </p>
            
            <!-- Memunculkan Area Layanan khusus jika metode Teknisi yang dipilih -->
            @if($transaksi->metode_pengiriman == 'technician' && $transaksi->area_layanan)
                <p><strong>Area Tujuan:</strong> {{ ucwords(str_replace('_', ' ', $transaksi->area_layanan)) }}</p>
            @endif
            <!-- 👆 Akhir Logika 👆 -->

            <p><strong>Metode Pembayaran:</strong> Transfer Bank ({{ $transaksi->status_pembayaran == 'dp' ? 'Down Payment 50%' : 'Lunas' }})</p>
            <p><strong>Berlaku Hingga:</strong> {{ date('d F Y', strtotime('+7 days')) }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th>Deskripsi Perangkat</th>
                <th style="width: 8%; text-align: center;">Qty</th>
                <th style="width: 20%; text-align: right;">Harga Satuan</th>
                <th style="width: 20%; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi->details as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $item->barang ? $item->barang->nama_barang : $item->nama_barang_custom }}</strong>
                    @if($item->barang && $item->barang->jenis == 'paket' && $item->barang->components->count() > 0)
                        <ul class="item-spec">
                            @foreach($item->barang->components as $comp)
                                <li>{{ $comp->nama_barang }} (x{{ $comp->pivot->qty }})</li>
                            @endforeach
                        </ul>
                    @endif
                </td>
                <td style="text-align: center;">{{ $item->qty }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-wrapper">
        <table class="summary-table">
            <tr>
                <td class="label">Subtotal</td>
                <td class="value">Rp {{ number_format($transaksi->total_harga - $transaksi->biaya_pengiriman, 0, ',', '.') }}</td>
            </tr>
            @if($transaksi->biaya_pengiriman > 0)
            <tr>
                <td class="label">Biaya Pengiriman</td>
                <td class="value">Rp {{ number_format($transaksi->biaya_pengiriman, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td class="label" style="color: #2980b9;"><strong>Grand Total</strong></td>
                <td class="value">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="terms">
        <strong>SYARAT & KETENTUAN:</strong><br>
        1. Harga sudah termasuk PPN 11% (jika berlaku).<br>
        2. Pembayaran ditransfer ke <strong>Bank BCA: 8765-4321-00 a.n PT Aneka Teknologi Solusindo</strong>.<br>
        3. Barang akan diproses setelah pembayaran / PO resmi diterima.<br>
        4. Masa berlaku penawaran ini adalah 7 hari kalender.<br>
        <!-- Peringatan Biaya Terpisah -->
        <span style="color: #c0392b; font-size: 12px; display: block; margin-top: 8px;">
            <strong>5. PENTING:</strong> Total harga yang tertera di atas <strong>belum termasuk</strong> biaya pengiriman / akomodasi kedatangan teknisi (biaya tersebut akan ditagihkan secara terpisah setelah proses perakitan selesai dan siap diberangkatkan ke lokasi Anda).
        </span>
    </div>

    <div class="signature-wrapper">
        <div>
            <p>Hormat Kami,</p>
            <div class="sign-space"></div>
            <p class="sign-name">Admin Penjualan</p>
            <p style="font-size: 11px; margin: 0;">PT. Aneka Teknologi Solusindo</p>
        </div>
        <div>
            <p>Disetujui Oleh,</p>
            <div class="sign-space"></div>
            <p class="sign-name">{{ $transaksi->nama_customer }}</p>
            <p style="font-size: 11px; margin: 0;">Customer / Perusahaan</p>
        </div>
    </div>
</div>

</body>
</html>