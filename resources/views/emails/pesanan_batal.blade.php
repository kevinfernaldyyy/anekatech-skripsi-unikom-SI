<!DOCTYPE html>
<html>
<head>
    <title>Pemberitahuan Pembatalan Pesanan - DiabloTech</title>
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
            /* Menggunakan aksen merah untuk notifikasi pembatalan */
            border-bottom: 3px solid #ef4444; 
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
        .alert-box { 
            background-color: #fef2f2; 
            border: 1px solid #fecaca; 
            border-left: 4px solid #ef4444; 
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
            width: 150px;
            color: #64748b;
        }
        .detail-value {
            font-weight: 600;
            color: #0f172a;
        }
        .info-system {
            background-color: #f8fafc;
            border-left: 4px solid #94a3b8;
            padding: 15px 20px;
            font-size: 14px;
            margin-top: 20px;
            border-radius: 0 4px 4px 0;
        }
        .btn-wrapper {
            text-align: center;
            margin: 35px 0 20px 0;
        }
        .btn { 
            display: inline-block; 
            background-color: #ef4444; 
            color: #ffffff; 
            text-decoration: none; 
            padding: 14px 35px; 
            border-radius: 4px; 
            font-weight: 600; 
            font-size: 15px; 
            box-shadow: 0 4px 6px rgba(239, 68, 68, 0.2);
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
            <img src="{{ url('img/logo.jpeg') }}" alt="Logo DiabloTech">
            <p>Sistem Internal - PT Aneka Teknologi Solusindo</p>
        </div>
        
        <div class="content">
            <h2>Pemberitahuan Pembatalan Pesanan</h2>
            
            <p>Yth. Tim Administrator,</p>
            
            <p>Melalui surat elektronik ini, sistem menginformasikan bahwa seorang pelanggan telah melakukan <strong>pembatalan pesanan secara mandiri</strong> sebelum tahap pembayaran atau proses operasional dilaksanakan.</p>
            
            <div class="alert-box">
                <div style="margin-bottom: 15px; font-weight: 600; color: #b91c1c; border-bottom: 1px solid #fecaca; padding-bottom: 10px;">Rincian Transaksi yang Dibatalkan:</div>
                <div class="detail-item">
                    <span class="detail-label">No. Transaksi</span>: 
                    <span class="detail-value">{{ $transaksi->no_transaksi }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Nama Pelanggan</span>: 
                    <span class="detail-value">{{ $transaksi->nama_customer }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Total Penagihan</span>: 
                    <span class="detail-value" style="color: #b91c1c;">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="info-system">
                <strong>Informasi Sistem Logistik:</strong><br>
                Seluruh alokasi stok komponen dan perangkat yang sebelumnya tertahan (<em>reserved</em>) untuk pesanan ini telah dikembalikan secara otomatis ke dalam basis data inventaris gudang. Tidak diperlukan penyesuaian stok manual.
            </div>

            <div class="btn-wrapper">
                <a href="{{ url('/login') }}" class="btn">Masuk ke Dasbor Admin</a>
            </div>

            <div class="signature">
                <p style="margin-bottom: 5px;">Hormat kami,</p>
                <p style="margin-top: 0; font-weight: 600; color: #0f172a; font-size: 16px;">Sistem Otomatisasi DiabloTech</p>
            </div>
        </div>
        
        <div class="footer">
            &copy; {{ date('Y') }} <strong>DIABLOTECH</strong>. Notifikasi internal otomatis.<br>
            Harap tidak membalas pesan yang dikirimkan secara otomatis oleh sistem ini.
        </div>
    </div>
</body>
</html>