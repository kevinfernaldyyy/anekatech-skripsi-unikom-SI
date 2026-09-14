<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Audit Aktivitas Admin</title>
    <style>
        @page { margin: 40px 50px; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #334155; line-height: 1.4; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .text-rose { color: #e11d48; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .layout-table td { border: none; padding: 0; vertical-align: top; }
        .header-section { border-bottom: 2px solid #e11d48; padding-bottom: 15px; margin-bottom: 20px; }
        .company-name { font-size: 24px; font-weight: 900; margin: 0; color: #0f172a; letter-spacing: 1px; }
        .company-sub { font-size: 12px; font-weight: bold; margin: 2px 0 5px 0; color: #e11d48; letter-spacing: 2px; text-transform: uppercase;}
        .report-title { font-size: 18px; font-weight: bold; margin: 0 0 10px 0; color: #0f172a; text-transform: uppercase; }
        .data-table th { background-color: #f1f5f9; color: #475569; font-size: 10px; text-transform: uppercase; padding: 10px; border: 1px solid #cbd5e1; border-bottom: 2px solid #94a3b8; }
        .data-table td { padding: 10px; border: 1px solid #e2e8f0; vertical-align: top; }
        .data-table tbody tr:nth-child(even) { background-color: #f8fafc; }
        .badge { display: inline-block; padding: 2px 5px; border-radius: 3px; font-size: 9px; font-weight: bold; text-transform: uppercase; border: 1px solid #cbd5e1; background-color: #f1f5f9;}
        footer { position: fixed; bottom: -20px; left: 0px; right: 0px; height: 20px; font-size: 9px; color: #94a3b8; }
        footer .pagenum:before { content: counter(page); }
    </style>
</head>
<body>

    <footer>
        <table class="layout-table">
            <tr>
                <td class="text-left">Di-generate oleh DiabloTech System</td>
                <td class="text-right">Halaman <span class="pagenum"></span></td>
            </tr>
        </table>
    </footer>

    <div class="header-section">
        <table class="layout-table">
            <tr>
                <td width="50%">
                    <h1 class="company-name">DIABLO<span class="text-rose">TECH</span></h1>
                    <p class="company-sub">PT Aneka Teknologi Solusindo</p>
                </td>
                <td width="50%" class="text-right">
                    <h2 class="report-title">Laporan Audit Admin</h2>
                    <p style="font-size: 10px; color: #475569; margin:0;">Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%" class="text-center">Waktu & IP</th>
                <th width="20%">Admin Eksekutor</th>
                <th width="15%" class="text-center">Aksi</th>
                <th width="45%">Deskripsi Perubahan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $index => $log)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">
                    <div class="font-bold">{{ $log->created_at->format('d/m/Y') }}</div>
                    <div style="font-size: 9px; color: #64748b;">{{ $log->created_at->format('H:i:s') }}</div>
                    <div style="font-size: 9px; color: #64748b;">IP: {{ $log->ip_address }}</div>
                </td>
                <td>
                    <div class="font-bold">{{ $log->user->name ?? 'Unknown' }}</div>
                    <div style="font-size: 9px; text-transform: uppercase; color: #e11d48;">{{ $log->user->role ?? 'N/A' }}</div>
                </td>
                <td class="text-center">
                    <span class="badge">{{ $log->action }}</span>
                </td>
                <td>{{ $log->description }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center" style="padding: 20px;">Belum ada aktivitas admin yang tercatat.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 40px; text-align: right; width: 250px; float: right;">
        <div style="font-size: 11px;">Bandung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
        <div style="margin: 20px 0;">
            <div style="display: inline-block; border: 2px solid #e11d48; color: #e11d48; padding: 6px 10px; border-radius: 4px; font-weight: 900; font-size: 10px; letter-spacing: 1px;">
                VERIFIED BY SYSTEM
            </div>
        </div>
        <div class="font-bold" style="text-decoration: underline;">{{ Auth::user()->name ?? 'Management' }}</div>
        <div style="font-size: 10px; color: #64748b;">Audit & Compliance</div>
    </div>

</body>
</html>