<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Receipt - {{ $transaksi->no_transaksi }}</title>
    <style>
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            color: #1e293b; 
            line-height: 1.6; 
            margin: 0; 
            padding: 0; 
            background-color: #f8fafc;
        }
        .container { 
            padding: 50px; 
            max-width: 850px; 
            margin: 30px auto; 
            background-color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
        }

        /* Watermark LUNAS / DP */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 130px;
            font-weight: 900;
            color: {{ $transaksi->status_pembayaran == 'lunas' ? 'rgba(5, 150, 105, 0.08)' : 'rgba(37, 99, 235, 0.06)' }};
            z-index: 0;
            pointer-events: none;
            text-transform: uppercase;
        }

        /* Header */
        .header-wrapper { 
            display: flex; 
            justify-content: space-between; 
            align-items: flex-start; 
            border-bottom: 4px solid #0f172a; 
            padding-bottom: 25px; 
            margin-bottom: 35px;
            position: relative;
            z-index: 1;
        }
        .brand-logo { width: 140px; margin-bottom: 12px; }
        .company-info h1 { 
            margin: 0; 
            color: #0f172a; 
            font-size: 24px; 
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .company-info p { margin: 2px 0; font-size: 12px; color: #64748b; }

        .doc-title { text-align: right; }
        .doc-title h2 { 
            margin: 0; 
            color: #0f172a; 
            font-size: 36px; 
            text-transform: uppercase; 
            font-weight: 900;
            line-height: 1;
        }
        .paid-badge {
            display: inline-block;
            margin-top: 10px;
            padding: 6px 20px;
            background-color: {{ $transaksi->status_pembayaran == 'lunas' ? '#059669' : '#2563eb' }};
            color: #fff;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Meta Info */
        .meta-container { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 50px; 
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }
        .meta-box h3 { 
            font-size: 11px; 
            text-transform: uppercase; 
            color: #94a3b8; 
            letter-spacing: 1.5px;
            margin-bottom: 10px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 5px;
        }
        .meta-box p { margin: 4px 0; font-size: 14px; }

        /* Table */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; position: relative; z-index: 1;}
        thead th { 
            background-color: #f8fafc; 
            color: #475569; 
            text-align: left; 
            padding: 15px 12px; 
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 700;
            border-bottom: 2px solid #e2e8f0;
        }
        tbody td { 
            padding: 18px 12px; 
            border-bottom: 1px solid #f1f5f9; 
            font-size: 14px; 
            vertical-align: top;
        }
        .item-name { font-weight: 700; color: #1e293b; margin-bottom: 4px; }
        .item-spec { font-size: 11px; color: #64748b; padding-left: 18px; margin: 0; }

        /* Totals */
        .summary-wrapper { 
            margin-top: 30px; 
            display: flex; 
            justify-content: space-between; 
            position: relative;
            z-index: 1;
        }
        .payment-method-box {
            width: 45%;
            padding: 20px;
            background-color: {{ $transaksi->status_pembayaran == 'lunas' ? '#f0fdf4' : '#eff6ff' }};
            border-radius: 12px;
            border: 1px solid {{ $transaksi->status_pembayaran == 'lunas' ? '#dcfce7' : '#bfdbfe' }};
        }
        .payment-method-box h4 { 
            margin: 0 0 10px 0; font-size: 13px; text-transform: uppercase; 
            color: {{ $transaksi->status_pembayaran == 'lunas' ? '#166534' : '#1e3a8a' }};
        }
        .payment-method-box p { 
            margin: 2px 0; font-size: 13px; 
            color: {{ $transaksi->status_pembayaran == 'lunas' ? '#15803d' : '#1d4ed8' }};
        }

        .summary-table { width: 350px; }
        .summary-table td { padding: 8px 12px; }
        .summary-table .label { text-align: right; color: #64748b; font-size: 14px; }
        .summary-table .value { text-align: right; font-weight: 700; color: #1e293b; width: 160px; }
        
        .total-row { background-color: #0f172a; border-radius: 8px; }
        .total-row td { color: #fff !important; padding: 15px 12px; }
        .total-row .value { font-size: 20px; color: #fff; }

        .dp-row { background-color: #2563eb; border-radius: 8px; }
        .dp-row td { color: #fff !important; padding: 15px 12px; }
        .dp-row .value { font-size: 20px; color: #fff; }

        /* Signature */
        .signature-section { 
            margin-top: 60px; 
            display: flex; 
            justify-content: space-between; 
            align-items: flex-end;
            position: relative;
            z-index: 1;
        }
        .sign-box { text-align: center; width: 220px; }
        .sign-img { width: 100px; height: auto; margin-bottom: -15px; opacity: 0.8; }
        .sign-line { border-top: 2px solid #1e293b; margin-top: 10px; padding-top: 5px; font-weight: 800; font-size: 14px; }

        @media print {
            body { background: none; }
            .container { padding: 0; margin: 0; width: 100%; box-shadow: none; border: none; }
            @page { margin: 1.5cm; }
            .watermark { color: {{ $transaksi->status_pembayaran == 'lunas' ? 'rgba(5, 150, 105, 0.12)' : 'rgba(37, 99, 235, 0.12)' }}; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="container">
    <div class="watermark">{{ $transaksi->status_pembayaran == 'lunas' ? 'PAID' : 'DP PAID' }}</div>

    <div class="header-wrapper">
        <div class="company-info">
            <img src="{{ asset('img/logo.jpeg') }}" alt="Logo DiabloTech" class="brand-logo">
            <h1>DIABLO<span>TECH</span></h1>
            <p>PT Aneka Teknologi Solusi Indo</p>
            <p>Gedung Teknologi Solusi Lt. 4, Bandung</p>
            <p>finance@diablotech.co.id | (022) 1234567</p>
        </div>
        <div class="doc-title">
            <h2>RECEIPT</h2>
            <p style="color: #64748b; font-weight: bold;">#{{ $transaksi->no_transaksi }}</p>
            <div class="paid-badge">{{ $transaksi->status_pembayaran == 'lunas' ? 'LUNAS / PAID' : 'DOWN PAYMENT (DP)' }}</div>
        </div>
    </div>

    <div class="meta-container">
        <div class="meta-box">
            <h3>Diterima Dari:</h3>
            <p><strong style="font-size: 18px; color: #0f172a;">{{ $transaksi->nama_customer }}</strong></p>
            <p>{{ $transaksi->customer->perusahaan ?? 'Pelanggan Perorangan' }}</p>
            <p>{{ $transaksi->alamat ?? '-' }}</p>
        </div>
        <div class="meta-box" style="text-align: right;">
            <h3>Detail Transaksi:</h3>
            <p>Tanggal Bayar: <strong>{{ date('d F Y', strtotime($transaksi->updated_at)) }}</strong></p>
            <p>No. Referensi: <strong>TRX-{{ $transaksi->id ?? rand(10000, 99999) }}</strong></p>
            
            <!-- 👇 TAMBAHAN INFO PENGIRIMAN DI RESI 👇 -->
            <div style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #e2e8f0;">
                <p>Pengiriman: <strong>
                    @if($transaksi->metode_pengiriman == 'pickup')
                        Ambil di Toko
                    @elseif($transaksi->metode_pengiriman == 'courier')
                        Kurir Ekspedisi
                    @elseif($transaksi->metode_pengiriman == 'technician')
                        Teknisi ke Lokasi
                    @else
                        {{ ucfirst($transaksi->metode_pengiriman ?? '-') }}
                    @endif
                </strong></p>
                @if($transaksi->metode_pengiriman == 'technician' && $transaksi->area_layanan)
                    <p>Area: <strong>{{ ucwords(str_replace('_', ' ', $transaksi->area_layanan)) }}</strong></p>
                @endif
            </div>
            <!-- 👆 AKHIR TAMBAHAN INFO PENGIRIMAN 👆 -->
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%; text-align: center;">NO</th>
                <th>DESKRIPSI PRODUK</th>
                <th style="width: 10%; text-align: center;">QTY</th>
                <th style="width: 20%; text-align: right;">HARGA SATUAN</th>
                <th style="width: 20%; text-align: right;">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi->details as $index => $item)
            <tr>
                <td style="text-align: center; color: #94a3b8;">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                <td>
                    <div class="item-name">{{ $item->barang ? $item->barang->nama_barang : $item->nama_barang_custom }}</div>
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
        <div class="payment-method-box">
            <h4>Informasi Pembayaran</h4>
            <p>Metode: <strong>Transfer Bank / Tunai</strong></p>
            <p>Status: <strong>Diterima & Terverifikasi</strong></p>
            <p style="margin-top: 10px; font-size: 11px; font-style: italic;">
                Bukti ini sah dan diterbitkan secara elektronik oleh sistem DiabloTech.
            </p>
        </div>
        
        <table class="summary-table">
            <tr>
                <td class="label">Subtotal</td>
                <td class="value">Rp {{ number_format($transaksi->total_harga - $transaksi->biaya_pengiriman, 0, ',', '.') }}</td>
            </tr>
            <!-- Menampilkan Biaya Pengiriman jika ada ongkir -->
            @if($transaksi->biaya_pengiriman > 0)
            <tr>
                <td class="label">Biaya Pengiriman</td>
                <td class="value">Rp {{ number_format($transaksi->biaya_pengiriman, 0, ',', '.') }}</td>
            </tr>
            @endif

            @if($transaksi->status_pembayaran == 'dp')
                <tr>
                    <td class="label" style="font-weight: bold;">TOTAL KESELURUHAN</td>
                    <td class="value">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                </tr>
                <tr class="dp-row">
                    <td class="label">DP DIBAYARKAN</td>
                    <td class="value">Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label" style="color: #ef4444; font-weight: bold; padding-top: 15px;">SISA TAGIHAN</td>
                    <td class="value" style="color: #ef4444; font-weight: bold; padding-top: 15px;">Rp {{ number_format($transaksi->total_harga - $transaksi->bayar, 0, ',', '.') }}</td>
                </tr>
            @else
                <tr class="total-row">
                    <td class="label">TOTAL DIBAYARKAN</td>
                    <td class="value">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                </tr>
            @endif
        </table>
    </div>

    <div class="signature-section">
        <div style="font-size: 12px; color: #94a3b8; max-width: 300px;">
            <p><strong>Catatan:</strong><br>
                {{ $transaksi->status_pembayaran == 'lunas' ? 'Terima kasih atas pesanan Anda! Garansi berlaku sejak tanggal invoice ini diterbitkan.' : 'Terima kasih! Pesanan Anda sedang diproses. Mohon lunasi sisa tagihan saat unit siap diambil/dikirim.' }}
            </p>
        </div>
        <div class="sign-box">
            <p style="font-size: 13px; margin-bottom: 5px;">Kasir / Bendahara,</p>
            <div style="height: 60px;">
                <span style="color: {{ $transaksi->status_pembayaran == 'lunas' ? '#059669' : '#2563eb' }}; font-weight: bold; font-family: 'Courier New', monospace; border: 2px solid {{ $transaksi->status_pembayaran == 'lunas' ? '#059669' : '#2563eb' }}; padding: 5px; display: inline-block; transform: rotate(-5deg);">VERIFIED BY SYSTEM</span>
            </div>
            <div class="sign-line">DIABLOTECH FINANCE</div>
        </div>
    </div>
</div>

</body>
</html>