<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penolakan Penawaran Pre-Order - DiabloTech POS</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #334155; -webkit-font-smoothing: antialiased;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f7f6; padding: 40px 20px;">
        <tr>
            <td align="center">
                <!-- Wrapper Card (520px Max Width) -->
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 520px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04); border: 1px solid #e5e7eb;">
                    
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
                                Pemberitahuan Pembatalan PO
                            </p>
                        </td>
                    </tr>

                    <!-- Content Body -->
                    <tr>
                        <td style="padding: 32px; font-size: 15px; line-height: 1.6; color: #374151;">
                            <h2 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 600; color: #111827;">
                                Halo Tim Admin! 👋
                            </h2>
                            <p style="margin: 0 0 20px 0;">
                                Kami menginformasikan bahwa pelanggan telah <strong style="color: #dc2626;">menolak</strong> penawaran harga Pre-Order yang diajukan. Sistem telah secara otomatis membatalkan pesanan ini.
                            </p>

                            <!-- Table Rincian Transaksi -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 0 0 24px 0; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px;">
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #475569; width: 40%;">No. Transaksi</td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-weight: 600;">{{ $transaksi->no_transaksi }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #475569;">Nama Pelanggan</td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-weight: 500;">{{ $transaksi->nama_customer }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 16px; font-weight: 600; color: #475569;">Status Pesanan</td>
                                    <td style="padding: 12px 16px; color: #dc2626; font-weight: 700;">Dibatalkan (Penawaran Ditolak)</td>
                                </tr>
                            </table>

                            <!-- Info Box (Red Accent) -->
                            <div style="background-color: #fef2f2; border-left: 3px solid #ef4444; padding: 16px; font-size: 13px; color: #991b1b; line-height: 1.5; margin-bottom: 28px; border-radius: 0 6px 6px 0;">
                                <strong style="color: #7f1d1d;">Catatan Sistem:</strong> Tidak ada tindakan lanjutan yang diperlukan. Anda tetap dapat meninjau riwayat transaksi ini melalui panel administrasi.
                            </div>

                            <!-- Tombol Action -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 0 0 10px 0;">
                                        <a href="{{ url('/admin/transaksi/' . $transaksi->id) }}" target="_blank" style="display: inline-block; padding: 12px 32px; background-color: #1e3a8a; color: #ffffff; text-decoration: none; font-weight: 600; font-size: 14px; border-radius: 6px; border: 1px solid #1e3a8a;">
                                            LIHAT RIWAYAT TRANSAKSI
                                        </a>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer Link Alternatif -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 20px 32px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #6b7280; line-height: 1.6; text-align: left;">
                            Jika tombol di atas tidak berfungsi, buka tautan berikut melalui peramban (browser):<br>
                            <a href="{{ url('/admin/transaksi/' . $transaksi->id) }}" style="color: #2563eb; word-break: break-all; text-decoration: none; margin-top: 6px; display: inline-block;">
                                {{ url('/admin/transaksi/' . $transaksi->id) }}
                            </a>
                        </td>
                    </tr>

                </table>

                <!-- Copyright Footer -->
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 520px; margin-top: 24px;">
                    <tr>
                        <td align="center" style="font-size: 12px; color: #9ca3af; line-height: 1.5;">
                            &copy; {{ date('Y') }} DiabloTech Solutions.<br>
                            Internal Order Management System
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>