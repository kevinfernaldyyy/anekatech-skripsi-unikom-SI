<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Siap Diambil - DiabloTech POS</title>
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
                                Pemberitahuan Pengambilan Pesanan
                            </p>
                        </td>
                    </tr>

                    <!-- Content Body -->
                    <tr>
                        <td style="padding: 32px; font-size: 15px; line-height: 1.6; color: #374151;">
                            <h2 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 600; color: #111827;">
                                Halo, {{ $transaksi->customer->nama_customer ?? 'Pelanggan Setia' }}! 👋
                            </h2>
                            <p style="margin: 0 0 20px 0;">
                                Kabar gembira! Pesanan PC/Komponen milikmu telah selesai melewati tahap <strong>Quality Control (QC)</strong> oleh teknisi kami dan kini statusnya <strong style="color: #16a34a;">SIAP DIAMBIL</strong>.
                            </p>

                            <!-- Table Rincian Transaksi -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 0 0 20px 0; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px;">
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #475569; width: 45%;">No. Invoice</td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-weight: 600;">{{ $transaksi->no_transaksi }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #475569;">Total Tagihan</td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; color: #2563eb; font-weight: 700; font-size: 15px;">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 16px; font-weight: 600; color: #475569;">Status Bayar</td>
                                    <td style="padding: 12px 16px; color: #1e293b; font-weight: 600; text-transform: uppercase;">{{ str_replace('_', ' ', $transaksi->status_pembayaran) }}</td>
                                </tr>
                            </table>

                            <!-- Info Pengambilan Box -->
                            <div style="background-color: #f8fafc; border-left: 3px solid #64748b; padding: 16px; font-size: 13px; color: #475569; line-height: 1.5; margin-bottom: 24px; border-radius: 0 6px 6px 0;">
                                <strong style="color: #334155;">Petunjuk Pengambilan:</strong> Silakan kunjungi toko kami dengan menunjukkan email ini atau bukti invoice transaksi pada saat pengambilan barang.<br><br>
                                <em style="color: #64748b;">*Catatan: Jika terdapat sisa pembayaran DP, mohon diselesaikan di meja kasir kami saat serah terima barang.</em>
                            </div>

                            <!-- Tombol Action -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 0 0 10px 0;">
                                        <a href="{{ route('customer.dashboard') }}" target="_blank" style="display: inline-block; padding: 12px 32px; background-color: #1e3a8a; color: #ffffff; text-decoration: none; font-weight: 600; font-size: 14px; border-radius: 6px; border: 1px solid #1e3a8a;">
                                            CEK DETAIL PESANAN
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 32px 0 0 0; font-size: 14px; color: #475569;">
                                Salam Hangat,<br>
                                <strong style="color: #111827;">Tim DiabloTech Solutions 🚀</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer Link Alternatif -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 20px 32px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #6b7280; line-height: 1.6; text-align: left;">
                            Jika tombol di atas tidak berfungsi, buka tautan berikut melalui peramban (browser) kamu:<br>
                            <a href="{{ route('customer.dashboard') }}" style="color: #2563eb; word-break: break-all; text-decoration: none; margin-top: 6px; display: inline-block;">
                                {{ route('customer.dashboard') }}
                            </a>
                        </td>
                    </tr>

                </table>

                <!-- Copyright Footer -->
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 520px; margin-top: 24px;">
                    <tr>
                        <td align="center" style="font-size: 12px; color: #9ca3af; line-height: 1.5;">
                            &copy; {{ date('Y') }} DiabloTech Solutions.<br>
                            Customer Pickup Notification System
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>