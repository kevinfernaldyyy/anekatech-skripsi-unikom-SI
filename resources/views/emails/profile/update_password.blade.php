<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan Perubahan Kata Sandi - DiabloTech POS</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #334155; -webkit-font-smoothing: antialiased;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f7f6; padding: 40px 20px;">
        <tr>
            <td align="center">
                <!-- Wrapper Card -->
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 520px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04); border: 1px solid #e5e7eb;">
                    
                    <!-- Top Accent Line -->
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
                                Security Notification
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
                                Kami ingin memberi tahu bahwa kata sandi akun <strong>DiabloTech POS</strong> milikmu telah berhasil diubah pada <strong>{{ now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB</strong>.
                            </p>

                            <!-- Tombol Login -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 10px 0 28px 0;">
                                        <a href="{{ url('/login') }}" target="_blank" style="display: inline-block; padding: 12px 32px; background-color: #1e3a8a; color: #ffffff; text-decoration: none; font-weight: 600; font-size: 14px; border-radius: 6px; border: 1px solid #1e3a8a;">
                                            MASUK KE AKUN
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Warning Box -->
                            <div style="background-color: #fef2f2; border-left: 3px solid #ef4444; padding: 16px; font-size: 13px; color: #991b1b; line-height: 1.5; border-radius: 0 6px 6px 0;">
                                <strong style="color: #7f1d1d;">⚠ Perhatian Keamanan:</strong> Jika kamu <strong>tidak merasa melakukan perubahan ini</strong>, segera lakukan <strong>Reset Password</strong> dan hubungi tim dukungan kami sesegera mungkin.
                            </div>

                            <p style="margin: 32px 0 0 0; font-size: 14px; color: #475569;">
                                Salam Hangat,<br>
                                <strong style="color: #111827;">Tim Keamanan DiabloTech Solutions 🚀</strong>
                            </p>
                        </td>
                    </tr>

                </table>

                <!-- Copyright Footer -->
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 520px; margin-top: 24px;">
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