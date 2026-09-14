<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Base Styles */
        body { 
            font-family: 'Segoe UI', Helvetica, Arial, sans-serif; 
            background-color: #f1f5f9; 
            margin: 0; 
            padding: 0; 
        }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f1f5f9; padding-bottom: 50px; }
        .main { 
            background-color: #ffffff; 
            margin: 30px auto; 
            width: 100%; 
            max-width: 600px; 
            border-radius: 16px; 
            box-shadow: 0 10px 25px rgba(30, 41, 59, 0.05);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        
        /* Header & Logo */
        .header { 
            background-color: #ffffff; 
            padding: 40px 30px 20px 30px; 
            text-align: center; 
            border-bottom: 1px solid #f1f5f9;
        }
        .logo-img {
            max-width: 160px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        /* Content Area */
        .content { padding: 40px; color: #334155; line-height: 1.7; }
        h2 { color: #059669; margin-top: 0; font-size: 24px; font-weight: 800; text-align: center; }
        p { margin: 18px 0; font-size: 15px; }
        
        /* Transaction Card */
        .invoice-card { 
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1px solid #bbf7d0; 
            border-radius: 12px; 
            padding: 30px; 
            margin: 25px 0;
            text-align: center;
        }
        .invoice-label { color: #166534; font-size: 12px; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 700; display: block; margin-bottom: 8px; }
        .invoice-value { color: #064e3b; font-size: 24px; font-weight: 800; display: block; }
        .invoice-status { color: #059669; font-size: 12px; font-weight: 700; margin-top: 5px; display: inline-block; padding: 2px 10px; border: 1px solid #059669; border-radius: 20px; }

        /* CTA Button */
        .btn-container { text-align: center; margin: 35px 0; }
        .btn { 
            background-color: #059669; 
            color: #ffffff !important; 
            padding: 16px 40px; 
            text-decoration: none; 
            border-radius: 8px; 
            font-weight: 700; 
            font-size: 15px;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.2);
        }
        
        /* Footer */
        .footer { 
            padding: 30px; 
            text-align: center; 
            font-size: 12px; 
            color: #94a3b8; 
            background-color: #f8fafc;
            border-top: 1px solid #f1f5f9;
        }
        .highlight { color: #059669; font-weight: 600; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main">
            <div class="header">
                <img src="{{ url('img/logo.jpeg') }}" alt="DIABLOTECH" class="logo-img">
                <div style="margin-top: 10px; color: #94a3b8; font-size: 10px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;">
                    Order Completion Notice
                </div>
            </div>

            <div class="content">
                <h2>Pesanan Selesai! 🎉</h2>
                <p>Halo <strong>{{ $transaksi->nama_customer }}</strong>,</p>
                <p>Kabar gembira! Transaksi Anda dengan nomor referensi <span class="highlight">#{{ $transaksi->no_transaksi }}</span> telah resmi selesai dan ditutup oleh sistem kami.</p>
                
                <div class="invoice-card">
                    <span class="invoice-label">Total Pembayaran</span>
                    <span class="invoice-value">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                    <span class="invoice-status">LUNAS / COMPLETED</span>
                </div>

                <p>Terima kasih telah mempercayakan perakitan dan kebutuhan IT Anda kepada <span class="highlight">DiabloTech</span>. Harapan kami, perangkat baru ini dapat mendukung produktivitas dan pengalaman digital Anda secara maksimal.</p>

                <p>Sebagai bentuk komitmen kami, riwayat garansi dan nota digital Anda telah kami arsipkan dengan aman di Portal Pelanggan.</p>

                <div class="btn-container">
                    <a href="{{ url('/login') }}" class="btn">Masuk ke Portal Pelanggan</a>
                </div>

                <p style="font-size: 14px; color: #64748b; font-style: italic;">
                    Butuh bantuan teknis? Tim support kami siap membantu Anda kapan saja melalui portal bantuan.
                </p>

                <div style="margin-top: 40px; border-top: 1px solid #f1f5f9; padding-top: 25px;">
                    <p style="margin-bottom: 0;">Salam hangat,</p>
                    <p style="margin-top: 5px; font-weight: bold; color: #1e293b; font-size: 16px;">Customer Success DiabloTech</p>
                </div>
            </div>

            <div class="footer">
                &copy; {{ date('Y') }} <strong>PT Aneka Teknologi Solusi Indo</strong>. All rights reserved.<br>
                Jl. Teknologi No. 123, Bandung, Jawa Barat.<br>
                <div style="margin-top: 8px;">
                    Email ini menandakan bahwa siklus transaksi telah berakhir. Terima kasih!
                </div>
            </div>
        </div>
    </div>
</body>
</html>