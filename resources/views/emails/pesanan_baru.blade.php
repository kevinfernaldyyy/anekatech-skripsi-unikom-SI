<!DOCTYPE html>
<html>
<head>
    <title>Pemberitahuan Transaksi Baru - DiabloTech</title>
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
            /* Garis atas dinamis berdasarkan status PO */
            border-top: 4px solid {{ $isPO ? '#f59e0b' : '#10b981' }};
            border-bottom: 3px solid #1e293b; 
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
        .status-badge {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #ffffff;
            background-color: {{ $isPO ? '#f59e0b' : '#10b981' }};
            margin-bottom: 20px;
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
            /* Border kiri dinamis */
            border-left: 4px solid {{ $isPO ? '#f59e0b' : '#10b981' }}; 
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
        .po-alert {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 15px 20px;
            border-radius: 4px;
            font-size: 13px;
            color: #b45309;
            margin-top: 20px;
            line-height: 1.6;
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
            <img src="{{ url('img/logo.jpeg') }}" alt="Logo DiabloTech">
            <p>Sistem Internal - PT Aneka Teknologi Solusindo</p>
        </div>
        
        <div class="content">
            <div align="center">
                <div class="status-badge">
                    {{ $isPO ? 'Permintaan Pre-Order (PO) Baru' : 'Pesanan Reguler Baru Masuk' }}
                </div>
            </div>
            
            <p>Yth. Tim Administrator,</p>
            
            <p>Melalui surat elektronik ini, sistem menginformasikan bahwa terdapat transaksi baru yang masuk ke dalam basis data dan memerlukan peninjauan lebih lanjut. Berikut adalah rincian transaksi tersebut:</p>
            
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
                    <span class="detail-label">Total Pembayaran</span>: 
                    <span class="detail-value" style="color: #2563eb;">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>

            @if($isPO)
                <div class="po-alert">
                    <strong>TINDAKAN DIPERLUKAN:</strong> Pesanan ini memuat komponen dengan status pengadaan <em>Pre-Order</em>. Dimohon untuk segera melakukan kalkulasi estimasi harga/waktu dan mengirimkan penawaran (<em>Offering Letter</em>) resmi kepada pelanggan terkait.
                </div>
            @endif

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