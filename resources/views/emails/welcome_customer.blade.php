<!DOCTYPE html>
<html>
<head>
    <title>Informasi Akses Portal Pelanggan - DiabloTech</title>
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
        .header h1 { 
            color: #ffffff; 
            margin: 0; 
            font-size: 24px; 
            letter-spacing: 2px; 
            text-transform: uppercase; 
        }
        .header h1 span { 
            color: #3b82f6; 
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
            padding: 20px; 
            border-radius: 4px; 
            margin: 30px 0; 
        }
        .info-box strong { 
            color: #0f172a; 
        }
        .credential-item {
            margin-bottom: 10px;
        }
        .credential-item:last-child {
            margin-bottom: 0;
        }
        .warning-box {
            color: #b91c1c;
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 15px 20px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            margin-top: 20px;
            line-height: 1.6;
        }
        .btn-wrapper {
            text-align: center;
            margin: 35px 0 10px 0;
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
        .footer-links a {
            color: #94a3b8;
            text-decoration: underline;
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ url('img/logo.jpeg') }}" alt="Logo DiabloTech">
            <p>PT Aneka Teknologi Solusindo</p>
        </div>
        
        <div class="content">
            <h2>Yth. {{ $customer->nama }},</h2>
            <p>Kami mengucapkan terima kasih atas kepercayaan Anda memilih DiabloTech sebagai mitra teknologi Anda. Pesanan Anda saat ini telah masuk ke dalam antrean sistem operasional kami dan akan segera diproses oleh tim ahli kami.</p>
            
            <p>Sebagai komitmen terhadap transparansi layanan, kami menyediakan akses ke <strong>Portal Pelanggan</strong>. Melalui portal ini, Anda dapat memantau seluruh proses secara <em>real-time</em>, mulai dari perakitan, pengujian performa (<em>stress test</em>), hingga status pengiriman.</p>

            <div class="info-box">
                <div style="margin-bottom: 15px; font-weight: 600; color: #0f172a; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">Informasi Akses Portal Anda:</div>
                <div class="credential-item">
                    ID Akses (Email) &nbsp;: <strong>{{ $customer->email }}</strong>
                </div>
                <div class="credential-item">
                    Kata Sandi Sementara : <strong style="font-family: 'Consolas', monospace; font-size: 16px;">{{ $passwordAsli }}</strong>
                </div>
            </div>

            <div class="warning-box">
                <strong>PENTING:</strong> Demi keamanan data dan privasi transaksi Anda, kami sangat menyarankan untuk segera mengubah kata sandi sementara di atas setelah Anda berhasil masuk (<em>login</em>) ke dalam sistem untuk pertama kalinya.
            </div>

            <div class="btn-wrapper">
                <a href="{{ url('/login') }}" class="btn">Masuk ke Portal Pelanggan</a>
            </div>

            <div class="signature">
                <p style="margin-bottom: 5px;">Hormat kami,</p>
                <p style="margin-top: 0; font-weight: 600; color: #0f172a; font-size: 16px;">Tim Operasional DiabloTech</p>
            </div>
        </div>
        
        <div class="footer">
            &copy; {{ date('Y') }} <strong>DIABLOTECH</strong>. Seluruh hak cipta dilindungi.<br>
            Gedung Teknologi Solusi, Bandung, Jawa Barat.<br>
            <div style="margin-top: 12px;">
                <span class="footer-links">
                    <a href="#">Pusat Bantuan</a> &bull; <a href="#">Kebijakan Privasi</a>
                </span>
            </div>
        </div>
    </div>
</body>
</html>