<!DOCTYPE html>
<html>
<head>
    <title>Pemberitahuan Pembayaran Masuk - PT Aneka Teknologi Solusindo</title>
    <style>
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            background-color: #f4f7fa; 
            margin: 0; 
            padding: 0; 
            -webkit-font-smoothing: antialiased;
        }
        .container { 
            max-width: 600px; 
            margin: 40px auto; 
            background-color: #ffffff; 
            border-radius: 8px; 
            overflow: hidden; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.05); 
            border: 1px solid #eaeaea; 
        }
        .header { 
            background-color: #0f172a; 
            padding: 35px 30px; 
            text-align: center; 
            border-bottom: 3px solid #2563eb; 
        }
        .header img {
            max-width: 160px;
            height: auto;
            display: block;
            margin: 0 auto 15px auto;
        }
        .header p {
            color: #94a3b8;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 10px 0 0 0;
        }
        .content { 
            padding: 40px; 
            color: #475569; 
            line-height: 1.8; 
            font-size: 15px; 
        }
        .content h2 { 
            color: #0f172a; 
            margin-top: 0; 
            font-size: 20px; 
            font-weight: 600; 
        }
        .info-box { 
            background-color: #f8fafc; 
            border: 1px solid #e2e8f0; 
            border-left: 4px solid #2563eb; 
            padding: 20px 25px; 
            border-radius: 4px; 
            margin: 30px 0; 
        }
        .detail-item {
            margin-bottom: 12px;
        }
        .detail-item:last-child {
            margin-bottom: 0;
        }
        .detail-label {
            display: inline-block;
            width: 170px;
            color: #64748b;
        }
        .detail-value {
            font-weight: 600;
            color: #0f172a;
        }
        .btn-wrapper {
            text-align: center;
            margin: 35px 0 20px 0;
        }
        .btn { 
            display: inline-block; 
            background-color: #2563eb; 
            color: #ffffff; 
            text-decoration: none; 
            padding: 14px 35px; 
            border-radius: 4px; 
            font-weight: 600; 
            font-size: 15px; 
            box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);
        }
        .signature {
            margin-top: 40px;
            padding-top: 25px;
            border-top: 1px solid #f1f5f9;
        }
        .footer { 
            background-color: #f8fafc; 
            padding: 25px 40px; 
            text-align: center; 
            color: #94a3b8; 
            font-size: 12px; 
            border-top: 1px solid #e2e8f0; 
            line-height: 1.6; 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ url('img/logo.jpeg') }}" alt="Logo AnekaTech">
            <p>Sistem Internal - PT Aneka Teknologi Solusindo</p>
        </div>
        
        <div class="content">
            <h2>Pemberitahuan Unggah Bukti Pembayaran</h2>
            
            <p>Yth. Tim Keuangan & Administrator,</p>
            
            <p>Melalui surat elektronik ini, sistem menginformasikan bahwa pelanggan telah mengunggah dokumen bukti pembayaran baru. Dimohon untuk segera melakukan rekonsiliasi mutasi rekening dan verifikasi terhadap pesanan berikut:</p>
            
            <div class="info-box">
                <div class="detail-item">
                    <span class="detail-label">No. Transaksi</span>: 
                    <span class="detail-value">{{ $transaksi->no_transaksi }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Nama Pelanggan</span>: 
                    <span class="detail-value">{{ $transaksi->nama_customer }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status Tagihan</span>: 
                    <span class="detail-value" style="text-transform: uppercase;">{{ $transaksi->status_pembayaran }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Total Penagihan</span>: 
                    <span class="detail-value">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Nominal Pembayaran</span>: 
                    <span class="detail-value" style="color: #059669;">Rp {{ number_format($transaksi->bayar > 0 ? $transaksi->bayar : $transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="btn-wrapper">
                <a href="{{ url('/login') }}" class="btn">Masuk untuk Verifikasi</a>
            </div>

            <div class="signature">
                <p style="margin-bottom: 5px;">Hormat kami,</p>
                <p style="margin-top: 0; font-weight: 600; color: #0f172a; font-size: 16px;">Sistem Otomatisasi AnekaTech</p>
            </div>
        </div>
        
        <div class="footer">
            &copy; {{ date('Y') }} <strong>PT Aneka Teknologi Solusindo</strong>. Notifikasi internal otomatis.<br>
            Harap segera memproses verifikasi agar operasional pesanan tidak terhambat.
        </div>
    </div>
</body>
</html>