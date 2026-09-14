<!DOCTYPE html>
<html>
<head>
    <title>Penyesuaian Tagihan Pengiriman - DiabloTech</title>
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
            <h2>Pemberitahuan Penyesuaian Tagihan Logistik</h2>
            
            <p>Yth. {{ $transaksi->customer->nama_customer }},</p>
            
            <p>Kami menginformasikan bahwa pesanan perakitan PC Anda dengan Nomor Invoice <strong>{{ $transaksi->no_transaksi }}</strong> telah berhasil melewati tahap perakitan serta <em>Quality Control</em> (QC), dan saat ini berada dalam status siap untuk diberangkatkan.</p>
            
            <p>Tim operasional logistik kami telah melakukan kalkulasi berat dan dimensi aktual untuk menentukan biaya pengiriman ke alamat tujuan Anda. Berikut adalah rincian penyesuaian tagihan akhir yang perlu diselesaikan:</p>

            <div class="info-box">
                <table width="100%" cellpadding="6" cellspacing="0" style="font-size: 14px;">
                    <tr>
                        <td style="color: #64748b;">Total Harga Perangkat</td>
                        <td align="right" style="font-weight: 600; color: #0f172a;">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Pembayaran Awal (DP)</td>
                        <td align="right" style="color: #b91c1c; font-weight: 600;">- Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-bottom: 1px dashed #cbd5e1; padding: 4px 0;"></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; padding-top: 10px;">Sisa Tagihan Perangkat</td>
                        <td align="right" style="font-weight: 600; color: #0f172a; padding-top: 10px;">Rp {{ number_format($transaksi->total_harga - $transaksi->bayar, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Biaya Pengiriman (Logistik)</td>
                        <td align="right" style="color: #059669; font-weight: 600;">+ Rp {{ number_format($transaksi->biaya_pengiriman, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><div style="border-bottom: 2px solid #e2e8f0; margin: 8px 0;"></div></td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; color: #0f172a; padding-top: 10px;">SISA PELUNASAN</td>
                        <td align="right" style="font-size: 18px; font-weight: bold; color: #2563eb; padding-top: 10px;">
                            Rp {{ number_format(($transaksi->total_harga - $transaksi->bayar) + $transaksi->biaya_pengiriman, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>
            </div>

            <p>Kami memohon perkenan Anda untuk masuk (<em>login</em>) ke Dasbor Pelanggan guna melakukan penyelesaian pembayaran dan mengunggah bukti pelunasan terkait. Proses penyerahan unit kepada pihak ekspedisi akan segera diotorisasi setelah pembayaran Anda terverifikasi oleh sistem kami.</p>
            
            <div class="btn-wrapper">
                <a href="{{ route('customer.orders.show', $transaksi->id) }}" class="btn">Selesaikan Pembayaran</a>
            </div>

            <div class="signature">
                <p style="margin-bottom: 5px;">Hormat kami,</p>
                <p style="margin-top: 0; font-weight: 600; color: #0f172a; font-size: 16px;">Tim Operasional Logistik DiabloTech</p>
            </div>
        </div>
        
        <div class="footer">
            &copy; {{ date('Y') }} <strong>DIABLOTECH</strong>. Seluruh hak cipta dilindungi.<br>
            Gedung Teknologi Solusi, Bandung, Jawa Barat.
        </div>
    </div>
</body>
</html>