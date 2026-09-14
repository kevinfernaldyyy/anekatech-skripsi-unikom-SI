<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemulihan Kata Sandi - DiabloTech POS</title>
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
                                Sistem Manajemen Rakit PC & POS
                            </p>
                        </td>
                    </tr>

                    <!-- Content Body -->
                    <tr>
                        <td style="padding: 32px; font-size: 15px; line-height: 1.6; color: #374151;">
                            <h2 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 600; color: #111827;">
                                Halo, {{ $user->name }}! 👋
                            </h2>
                            <p style="margin: 0 0 20px 0;">
                                Kami menerima permintaan untuk mengatur ulang kata sandi (reset password) pada akun <strong>DiabloTech POS</strong> milikmu.
                            </p>
                            <p style="margin: 0 0 28px 0;">
                                Silakan klik tombol di bawah ini untuk membuat kata sandi baru:
                            </p>

                            <!-- Tombol Reset Password -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 0 0 32px 0;">
                                        <a href="{{ $url }}" target="_blank" style="display: inline-block; padding: 12px 32px; background-color: #1e3a8a; color: #ffffff; text-decoration: none; font-weight: 600; font-size: 14px; border-radius: 6px; border: 1px solid #1e3a8a;">
                                            ATUR ULANG KATA SANDI
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Info Warning -->
                            <div style="background-color: #f8fafc; border-left: 3px solid #64748b; padding: 16px; font-size: 13px; color: #475569; line-height: 1.5;">
                                <strong style="color: #334155;">Catatan Penting:</strong> Tautan pemulihan ini hanya berlaku selama <strong>60 menit</strong>. Jika kamu tidak pernah meminta pengaturan ulang kata sandi, abaikan email ini.
                            </div>

                            <p style="margin: 32px 0 0 0; font-size: 14px; color: #475569;">
                                Salam Hangat,<br>
                                <strong style="color: #111827;">Tim Keamanan DiabloTech Solutions 🚀</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer Link Alternatif -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 24px 32px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #6b7280; line-height: 1.6; text-align: left;">
                            Jika tombol di atas tidak berfungsi, salin dan tempel URL berikut ke peramban (browser) kamu:<br>
                            <a href="{{ $url }}" style="color: #2563eb; word-break: break-all; text-decoration: none; margin-top: 8px; display: inline-block;">
                                {{ $url }}
                            </a>
                        </td>
                    </tr>

                </table>

                <!-- Copyright Footer -->
                <table width="100%" max-width="520" cellpadding="0" cellspacing="0" style="max-width: 520px; margin-top: 24px;">
                    <tr>
                        <td align="center" style="font-size: 12px; color: #9ca3af; line-height: 1.5;">
                            &copy; {{ date('Y') }} DiabloTech Solutions.<br>
                            Secure Gateway System
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>