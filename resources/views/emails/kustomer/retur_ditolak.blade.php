<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klaim Garansi Ditolak - DiabloTech</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #334155;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="100%" max-width="500" cellpadding="0" cellspacing="0" style="max-width: 500px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0;">
                    
                    <!-- Top Gradient Accent (Merah) -->
                    <tr>
                        <td style="height: 6px; background: linear-gradient(90deg, #ef4444, #b91c1c);"></td>
                    </tr>

                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 28px 32px 16px 32px;">
                            <h1 style="margin: 0; font-size: 22px; font-weight: 800; color: #0f172a;">
                                DIABLO<span style="color: #ef4444;">TECH</span>
                            </h1>
                            <p style="margin: 4px 0 0 0; font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                Update Status Retur Pesanan
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 0 32px 32px 32px; font-size: 14px; line-height: 1.6; color: #475569;">
                            <div style="background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; color: #991b1b; font-weight: 600;">
                                ❌ Klaim Retur / Garansi Tidak Dapat Diproses
                            </div>

                            <p style="margin: 0 0 16px 0;">
                                Halo, berdasarkan hasil inspeksi mendalam oleh Teknisi kami terhadap pesanan <strong>#{{ $transaksi->id ?? $retur->transaksi_id }}</strong>, dengan berat hati kami sampaikan bahwa klaim garansi/retur Anda dinyatakan <strong>DITOLAK / GUGUR</strong>.
                            </p>

                            <!-- Alasan Penolakan dari Admin (Kalau ada kolomnya di tabel retur) -->
                            <div style="background-color: #f8fafc; padding: 12px 16px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 16px; font-size: 13px;">
                                <strong>Catatan Inspeksi Teknisi:</strong><br>
                                {{ $retur->alasan_penolakan ?? $retur->catatan ?? 'Kerusakan disebabkan oleh faktor di luar cakupan garansi (seperti kesalahan penggunaan/human error, cacat fisik, atau segel garansi rusak/hilang).' }}
                            </div>

                            <p style="margin: 0 0 24px 0;">
                                Unit/barang Anda akan kami kirimkan kembali ke alamat tujuan sesuai dengan kesepakatan awal. Silakan hubungi Admin melalui kontak WhatsApp toko jika memerlukan penjelasan teknis lebih lanjut.
                            </p>

                            <p style="margin: 0; font-size: 14px; color: #334155;">
                                Salam Hangat,<br>
                                <strong>Tim Support DiabloTech Solutions 🚀</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 16px 32px; border-top: 1px solid #e2e8f0; font-size: 11px; color: #64748b; text-align: center;">
                            &copy; {{ date('Y') }} DiabloTech Solutions &bull; Secure Warranty Gateway
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>