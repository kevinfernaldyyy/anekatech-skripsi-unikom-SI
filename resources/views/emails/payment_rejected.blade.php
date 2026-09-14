<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Pembayaran Belum Valid - DiabloTech</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #334155; -webkit-font-smoothing: antialiased;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f7f6; padding: 40px 20px;">
        <tr>
            <td align="center">
                <!-- Wrapper Card -->
                <table width="100%" max-width="520" cellpadding="0" cellspacing="0" style="max-width: 520px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04); border: 1px solid #e5e7eb;">
                    
                    <!-- Top Accent Line (Warning Red) -->
                    <tr>
                        <td style="height: 4px; background-color: #dc2626;"></td>
                    </tr>

                    <!-- Header & Title -->
                    <tr>
                        <td align="center" style="padding: 40px 32px 24px 32px; border-bottom: 1px solid #f3f4f6;">
                            <h1 style="margin: 0; font-size: 22px; font-weight: 800; color: #111827; letter-spacing: 0.5px; text-transform: uppercase;">
                                DIABLO<span style="color: #2563eb;">TECH</span>
                            </h1>
                            <p style="margin: 6px 0 0 0; font-size: 12px; color: #dc2626; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                {{ $jenis == 'pelunasan' ? 'Pelunasan Belum Valid ⚠️' : 'Pembayaran Belum Valid ⚠️' }}
                            </p>
                        </td>
                    </tr>

                    <!-- Content Body -->
                    <tr>
                        <td style="padding: 32px; font-size: 15px; line-height: 1.6; color: #374151;">
                            <p style="margin: 0 0 16px 0;">
                                Halo <strong style="color: #111827;">{{ $transaksi->nama_customer }}</strong>,
                            </p>
                            <p style="margin: 0 0 24px 0;">
                                Mohon maaf, bukti <strong style="color: #111827;">{{ $jenis == 'pelunasan' ? 'PELUNASAN (Sisa Tagihan + Ongkir)' : 'PEMBAYARAN AWAL' }}</strong> untuk pesanan Anda telah kami periksa, namun <strong style="color: #dc2626;">belum dapat kami validasi</strong>. Hal ini biasanya terjadi karena foto buram, dana belum masuk ke mutasi rekening, atau nominal transfer belum sesuai.
                            </p>

                            <!-- Detail Warning Box -->
                            <div style="background-color: #fef2f2; border: 1px solid #fecaca; border-left: 4px solid #dc2626; border-radius: 6px; padding: 20px; margin-bottom: 24px;">
                                <table width="100%" cellpadding="0" cellspacing="0" style="font-size: 13px; color: #475569;">
                                    <tr>
                                        <td style="padding-bottom: 8px; font-weight: 600; color: #64748b;">No. Invoice:</td>
                                        <td style="padding-bottom: 8px; text-align: right; font-weight: 700; color: #111827;">{{ $transaksi->no_transaksi }}</td>
                                    </tr>
                                    @if($jenis == 'pelunasan')
                                        <tr>
                                            <td style="padding-bottom: 8px; font-weight: 600; color: #64748b;">Sisa yg Harus Dilunasi:</td>
                                            <td style="padding-bottom: 8px; text-align: right; font-weight: 700; color: #dc2626;">Rp {{ number_format($transaksi->total_harga - $transaksi->bayar, 0, ',', '.') }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td style="padding-bottom: 8px; font-weight: 600; color: #64748b;">Total Tagihan:</td>
                                            <td style="padding-bottom: 8px; text-align: right; font-weight: 700; color: #111827;">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td style="font-weight: 600; color: #64748b;">Status Saat Ini:</td>
                                        <td style="text-align: right; font-weight: 700; color: #dc2626;">Menunggu Bukti Transfer Ulang</td>
                                    </tr>
                                </table>
                            </div>

                            <p style="margin: 0 0 28px 0;">
                                Pesanan Anda tetap tersimpan dengan aman di sistem kami. Silakan periksa kembali bukti transfer Anda dan unggah ulang berkas yang jelas melalui portal pelanggan:
                            </p>

                            <!-- Button Upload Ulang -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 0 0 32px 0;">
                                        <a href="{{ route('customer.orders.show', $transaksi->id) }}" target="_blank" style="display: inline-block; padding: 12px 32px; background-color: #dc2626; color: #ffffff; text-decoration: none; font-weight: 600; font-size: 14px; border-radius: 6px; border: 1px solid #dc2626;">
                                            UNGGAH ULANG STRUK
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0; font-size: 14px; color: #475569;">
                                Salam Hangat,<br>
                                <strong style="color: #111827;">Tim Keuangan DiabloTech</strong>
                            </p>
                        </td>
                    </tr>

                </table>

                <!-- Copyright Footer -->
                <table width="100%" max-width="520" cellpadding="0" cellspacing="0" style="max-width: 520px; margin-top: 24px;">
                    <tr>
                        <td align="center" style="font-size: 11px; color: #9ca3af; line-height: 1.6;">
                            Ini adalah email otomatis, mohon tidak membalas email ini.<br>
                            &copy; {{ date('Y') }} PT Aneka Teknologi Solusi Indo (DiabloTech).
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>