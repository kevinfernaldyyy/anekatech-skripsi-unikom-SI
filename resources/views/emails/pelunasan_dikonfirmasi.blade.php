<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil Dikonfirmasi - DiabloTech</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #334155; -webkit-font-smoothing: antialiased;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f7f6; padding: 40px 20px;">
        <tr>
            <td align="center">
                <!-- Wrapper Card -->
                <table width="100%" max-width="520" cellpadding="0" cellspacing="0" style="max-width: 520px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04); border: 1px solid #e5e7eb;">
                    
                    <!-- Top Accent Line (Corporate Navy) -->
                    <tr>
                        <td style="height: 4px; background-color: #1e3a8a;"></td>
                    </tr>

                    <!-- Header & Title -->
                    <tr>
                        <td align="center" style="padding: 40px 32px 24px 32px; border-bottom: 1px solid #f3f4f6;">
                            <h1 style="margin: 0; font-size: 22px; font-weight: 800; color: #111827; letter-spacing: 0.5px; text-transform: uppercase;">
                                DIABLO<span style="color: #2563eb;">TECH</span>
                            </h1>
                            <p style="margin: 6px 0 0 0; font-size: 12px; color: #16a34a; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                Pembayaran Lunas! 🎉
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
                                Terima kasih! Kami telah menerima dan memverifikasi seluruh pembayaran Anda. Pesanan Anda saat ini berstatus <strong style="color: #16a34a;">LUNAS</strong> dan akan segera kami proses ke tahap selanjutnya.
                            </p>

                            <!-- Detail Transaction Box -->
                            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid #16a34a; border-radius: 6px; padding: 20px; margin-bottom: 24px;">
                                <table width="100%" cellpadding="0" cellspacing="0" style="font-size: 13px; color: #475569;">
                                    <tr>
                                        <td style="padding-bottom: 8px; font-weight: 600; color: #64748b;">No. Transaksi:</td>
                                        <td style="padding-bottom: 8px; text-align: right; font-weight: 700; color: #111827;">{{ $transaksi->no_transaksi }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding-bottom: 8px; font-weight: 600; color: #64748b;">Status Pembayaran:</td>
                                        <td style="padding-bottom: 8px; text-align: right; font-weight: 700; color: #16a34a; text-transform: uppercase;">LUNAS</td>
                                    </tr>
                                    <tr>
                                        <td style="padding-bottom: 8px; font-weight: 600; color: #64748b;">Total Dibayar:</td>
                                        <td style="padding-bottom: 8px; text-align: right; font-weight: 700; color: #111827;">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600; color: #64748b;">Metode Pengiriman:</td>
                                        <td style="text-align: right; font-weight: 700; color: #111827; text-transform: uppercase;">{{ $transaksi->metode_pengiriman }}</td>
                                    </tr>
                                </table>
                            </div>

                            <p style="margin: 0 0 28px 0;">
                                Anda dapat memantau status perkembangan pesanan secara <em>real-time</em> melalui portal pelanggan:
                            </p>

                            <!-- Button Lacak -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 0 0 32px 0;">
                                        <a href="{{ route('customer.dashboard') }}" target="_blank" style="display: inline-block; padding: 12px 32px; background-color: #1e3a8a; color: #ffffff; text-decoration: none; font-weight: 600; font-size: 14px; border-radius: 6px; border: 1px solid #1e3a8a;">
                                            LACAK STATUS PESANAN
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0; font-size: 14px; color: #475569;">
                                Salam Hangat,<br>
                                <strong style="color: #111827;">Tim Operasional DiabloTech</strong>
                            </p>
                        </td>
                    </tr>

                </table>

                <!-- Copyright Footer -->
                <table width="100%" max-width="520" cellpadding="0" cellspacing="0" style="max-width: 520px; margin-top: 24px;">
                    <tr>
                        <td align="center" style="font-size: 11px; color: #9ca3af; line-height: 1.6;">
                            Email ini adalah bukti pembayaran digital yang sah.<br>
                            &copy; {{ date('Y') }} PT Aneka Teknologi Solusi Indo (DiabloTech).
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>