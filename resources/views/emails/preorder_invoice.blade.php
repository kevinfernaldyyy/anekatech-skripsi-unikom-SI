<!DOCTYPE html>
<html>
<head>
    <title>Pemberitahuan Konfirmasi Pre-Order - PT Aneka Teknologi Solusindo</title>
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
            width: 150px;
            color: #64748b;
        }
        .detail-value {
            font-weight: 600;
            color: #0f172a;
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
            <p>PT Aneka Teknologi Solusindo</p>
        </div>
        
        <div class="content">
            <h2>Pemberitahuan Konfirmasi Pre-Order</h2>
            
            <p>Yth. {{ $customer->nama }},</p>
            
            <p>Kami mengucapkan terima kasih atas kepercayaan Anda melakukan pemesanan (<em>Pre-Order</em>) di PT Aneka Teknologi Solusindo. Melalui surat elektronik ini, kami menginformasikan bahwa rincian pesanan Anda telah berhasil diverifikasi dan tercatat dalam sistem kami.</p>
            
            <div class="info-box">
                <div style="margin-bottom: 15px; font-weight: 600; color: #0f172a; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">Rincian Pre-Order Anda:</div>
                <div class="detail-item">
                    <span class="detail-label">No. Registrasi PO</span>: 
                    <span class="detail-value">{{ $preOrder->no_po }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Tanggal Pemesanan</span>: 
                    <span class="detail-value">{{ $preOrder->created_at->format('d M Y') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Estimasi Ketersediaan</span>: 
                    <span class="detail-value" style="color: #2563eb;">{{ $preOrder->estimasi_ready ? \Carbon\Carbon::parse($preOrder->estimasi_ready)->format('d M Y') : 'Akan Diinformasikan Lebih Lanjut' }}</span>
                </div>
            </div>

            <p>Saat ini, tim pengadaan kami sedang memproses pesanan Anda dengan pihak distributor utama. Pemberitahuan selanjutnya mengenai ketersediaan dan kedatangan unit akan kami sampaikan secara berkala melalui surat elektronik ini atau nomor WhatsApp Anda yang telah terdaftar.</p>
            
            <p>Apabila Anda membutuhkan bantuan atau memiliki pertanyaan lebih lanjut mengenai status pemesanan ini, Anda dapat langsung membalas surat elektronik ini untuk terhubung dengan layanan pelanggan kami.</p>

            <div class="signature">
                <p style="margin-bottom: 5px;">Hormat kami,</p>
                <p style="margin-top: 0; font-weight: 600; color: #0f172a; font-size: 16px;">Tim Pengadaan & Operasional</p>
            </div>
        </div>
        
        <div class="footer">
            &copy; {{ date('Y') }} <strong>PT Aneka Teknologi Solusindo</strong>. Seluruh hak cipta dilindungi.<br>
            Gedung Teknologi Solusi, Bandung, Jawa Barat.
        </div>
    </div>
</body>
</html>