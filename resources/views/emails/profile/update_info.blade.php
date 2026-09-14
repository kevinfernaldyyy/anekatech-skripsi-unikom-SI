<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan Perubahan Profil - DiabloTech</title>
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
                                Perubahan Profil Akun 🔔
                            </p>
                        </td>
                    </tr>

                    <!-- Content Body -->
                    <tr>
                        <td style="padding: 32px; font-size: 15px; line-height: 1.6; color: #374151;">
                            <p style="margin: 0 0 16px 0;">
                                Halo <strong style="color: #111827;">{{ $user->name }}</strong>,
                            </p>
                            <p style="margin: 0 0 24px 0;">
                                Kami ingin memberitahukan bahwa informasi profil pada akun <strong style="color: #111827;">DiabloTech</strong> Anda telah berhasil diperbarui.
                            </p>

                            <!-- Detail Changes Box -->
                            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid #16a34a; border-radius: 6px; padding: 20px; margin-bottom: 24px;">
                                <table width="100%" cellpadding="0" cellspacing="0" style="font-size: 13px; color: #475569;">
                                    <tr>
                                        <td style="padding-bottom: 8px; font-weight: 600; color: #64748b;">Waktu Perubahan:</td>
                                        <td style="padding-bottom: 8px; text-align: right; font-weight: 700; color: #111827;">{{ now()->format('d F Y, H:i') }} WIB</td>
                                    </tr>
                                    <tr>
                                        <td style="padding-bottom: 8px; font-weight: 600; color: #64748b;">Email Akun:</td>
                                        <td style="padding-bottom: 8px; text-align: right; font-weight: 700; color: #111827;">{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600; color: #64748b;">Status:</td>
                                        <td style="text-align: right; font-weight: 700; color: #16a34a;">Profil Berhasil Diperbarui</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Warning Box -->
                            <div style="background-color: #fff7ed; border: 1px solid #ffedd5; border-left: 4px solid #f59e0b; border-radius: 6px; padding: 16px; font-size: 13px; color: #7c2d12; line-height: 1.5; margin-bottom: 28px;">
                                <strong style="color: #9a3412;">Informasi Penting:</strong> Jika Anda tidak merasa melakukan perubahan profil ini, segera masuk ke akun Anda dan ubah kata sandi untuk menjaga keamanan akun, atau hubungi tim dukungan DiabloTech sesegera mungkin.
                            </div>

                            <!-- Button Lihat Profil -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 0 0 32px 0;">
                                        <a href="{{ url('/profile') }}" target="_blank" style="display: inline-block; padding: 12px 32px; background-color: #1e3a8a; color: #ffffff; text-decoration: none; font-weight: 600; font-size: 14px; border-radius: 6px; border: 1px solid #1e3a8a;">
                                            LIHAT PROFIL SAYA
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Security Tips -->
                            <div style="border-top: 1px solid #e5e7eb; pt-4; padding-top: 20px; font-size: 12px; color: #6b7280; line-height: 1.6;">
                                <strong style="color: #374151;">Tips Keamanan Akun:</strong>
                                <ul style="margin: 8px 0 0 0; padding-left: 18px;">
                                    <li>Pastikan informasi profil selalu menggunakan data yang valid.</li>
                                    <li>Jangan membagikan kredensial akun kepada pihak manapun.</li>
                                    <li>Gunakan kata sandi yang kuat dan ubah secara berkala.</li>
                                </ul>
                            </div>

                            <p style="margin: 28px 0 0 0; font-size: 14px; color: #475569;">
                                Salam Hangat,<br>
                                <strong style="color: #111827;">Tim Keamanan DiabloTech</strong>
                            </p>
                        </td>
                    </tr>

                </table>

                <!-- Copyright Footer -->
                <table width="100%" max-width="520" cellpadding="0" cellspacing="0" style="max-width: 520px; margin-top: 24px;">
                    <tr>
                        <td align="center" style="font-size: 11px; color: #9ca3af; line-height: 1.6;">
                            Email ini dikirim secara otomatis oleh sistem DiabloTech.<br>
                            &copy; {{ date('Y') }} PT Aneka Teknologi Solusi Indo. All rights reserved.
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>