<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Dikirim - DiabloTech</title>
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
                            <p style="margin: 6px 0 0 0; font-size: 12px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                Konfirmasi Pengiriman Paket 🚚
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
                                Kabar gembira! Pesanan PC Anda dengan nomor invoice <strong style="color: #111827;">{{ $transaksi->no_transaksi }}</strong> telah selesai dikemas dengan aman dan saat ini sedang dalam perjalanan menuju alamat tujuan.
                            </p>

                            <!-- Resi Box -->
                            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid #2563eb; border-radius: 6px; padding: 20px; text-align: center; margin-bottom: 24px;">
                                <p style="margin: 0; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">
                                    Informasi Pelacakan Paket
                                </p>
                                <p style="margin: 8px 0 0 0; font-size: 20px; font-weight: 800; color: #1e3a8a; letter-spacing: 1px;">
                                    {{ $transaksi->resi_pengiriman }}
                                </p>
                            </div>

                            <p style="margin: 0 0 28px 0;">
                                Anda dapat melacak status pengiriman dan melihat foto bukti *packing* langsung melalui portal pelanggan kami:
                            </p>

                            <!-- Button Lacak -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 0 0 32px 0;">
                                        <a href="{{ route('login') }}" target="_blank" style="display: inline-block; padding: 12px 32px; background-color: #1e3a8a; color: #ffffff; text-decoration: none; font-weight: 600; font-size: 14px; border-radius: 6px; border: 1px solid #1e3a8a;">
                                            LACAK PESANAN SAYA
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <div style="background-color: #f8fafc; border-radius: 6px; padding: 16px; font-size: 13px; color: #64748b; line-height: 1.5;">
                                <strong style="color: #334155;">Catatan Tambahan:</strong> Terima kasih telah mempercayakan perakitan PC Anda kepada DiabloTech. Jika terdapat kendala saat paket diterima, Anda dapat mengajukan retur langsung melalui portal pelanggan.
                            </div>

                            <p style="margin: 28px 0 0 0; font-size: 14px; color: #475569;">
                                Salam Hangat,<br>
                                <strong style="color: #111827;">Tim Logistik DiabloTech</strong>
                            </p>
                        </td>
                    </tr>

                </table>

                <!-- Copyright Footer -->
                <table width="100%" max-width="520" cellpadding="0" cellspacing="0" style="max-width: 520px; margin-top: 24px;">
                    <tr>
                        <td align="center" style="font-size: 11px; color: #9ca3af; line-height: 1.6;">
                            &copy; {{ date('Y') }} PT Aneka Teknologi Solusi Indo.<br>
                            Sistem Logistik & Distribusi<br>
                            Bandung, Indonesia
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>