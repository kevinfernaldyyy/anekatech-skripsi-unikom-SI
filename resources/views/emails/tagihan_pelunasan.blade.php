<!DOCTYPE html>
<html>
<head>
    <title>Pemberitahuan Pelunasan Pesanan - DiabloTech</title>
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
            padding: 20px; 
            border-radius: 4px; 
            margin: 30px 0; 
        }
        .info-box strong { 
            color: #0f172a; 
        }
        .detail-item {
            margin-bottom: 10px;
        }
        .detail-item:last-child {
            margin-bottom: 0;
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
            <p>PT Aneka Teknologi Solusindo</p>
        </div>
        
        <div class="content">
            @if($transaksi->is_rakit == 1)
                <h2>Pemberitahuan Penyelesaian Perakitan</h2>
            @else
                <h2>Pemberitahuan Penyelesaian Pesanan</h2>
            @endif
            
            <p>Yth. {{ $transaksi->nama_customer }},</p>
            
            @if($transaksi->is_rakit == 1)
                <p>Kami menginformasikan bahwa proses perakitan dan Uji Kelayakan (<em>Quality Control</em>) untuk unit komputer pesanan Anda telah selesai dilaksanakan sesuai dengan standar operasional kami.</p>
            @else
                <p>Kami menginformasikan bahwa tim operasional kami telah selesai menyiapkan dan memverifikasi kelengkapan fisik seluruh komponen pada pesanan Anda.</p>
            @endif

            <div class="info-box">
                <div style="margin-bottom: 15px; font-weight: 600; color: #0f172a; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">Rincian Penagihan:</div>
                <div class="detail-item">
                    No. Invoice &nbsp; &nbsp; &nbsp; &nbsp;: <strong>{{ $transaksi->no_transaksi }}</strong>
                </div>
                <div class="detail-item">
                    Total Tagihan &nbsp; &nbsp; : <strong>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</strong>
                </div>
                <div class="detail-item">
                    Status &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: <strong style="color: #b91c1c;">Menunggu Pelunasan Tagihan Akhir</strong>
                </div>
            </div>

            @if($transaksi->status_pembayaran == 'lunas')
                <p>Terdapat penyesuaian penagihan atas penambahan biaya pengiriman logistik / layanan teknisi kunjungan (<em>Home Service</em>) sebesar <strong style="color: #b91c1c;">Rp {{ number_format($transaksi->total_harga - $transaksi->bayar, 0, ',', '.') }}</strong>.</p>
                <p>Kami memohon perkenan Anda untuk menyelesaikan penagihan tambahan tersebut dan mengunggah bukti transfer pada Dasbor Pelanggan, agar tim teknisi/logistik kami dapat segera menuju ke lokasi Anda.</p>
            @else
                <p>Berdasarkan metode pembayaran Uang Muka (<em>Down Payment</em>) yang Anda gunakan, kami memohon perkenan Anda untuk segera melakukan pelunasan atas sisa tagihan yang tertera agar unit pesanan dapat diproses ke tahap serah-terima atau pengiriman.</p>
            @endif

            <div class="btn-wrapper">
                <a href="{{ route('customer.orders.show', $transaksi->id) }}" class="btn">Tinjau Tagihan pada Dasbor</a>
            </div>

            <p style="font-size: 13px; color: #64748b; font-style: italic;">Catatan: Apabila Anda telah memilih metode Pengambilan Mandiri (Pickup) di toko, pelunasan sisa tagihan juga dapat dilakukan secara langsung di meja kasir kami menggunakan metode tunai atau transfer.</p>

            <div class="signature">
                <p style="margin-bottom: 5px;">Hormat kami,</p>
                <p style="margin-top: 0; font-weight: 600; color: #0f172a; font-size: 16px;">Tim Keuangan & Operasional DiabloTech</p>
            </div>
        </div>
        
        <div class="footer">
            &copy; {{ date('Y') }} <strong>DIABLOTECH</strong>. Seluruh hak cipta dilindungi.<br>
            Gedung Teknologi Solusi, Bandung, Jawa Barat.
        </div>
    </div>
</body>
</html>