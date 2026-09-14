<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - {{ $jenis }}</title>
    <style>
        /* Setup Kertas & Font */
        @page { margin: 40px 50px; }
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            font-size: 11px; 
            color: #334155; 
            line-height: 1.4;
        }

        /* Utility Classes */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .text-blue { color: #2563eb; }
        .text-slate { color: #64748b; }
        .uppercase { text-transform: uppercase; }

        /* Layout Tables (Pengganti Flexbox untuk DomPDF) */
        table { width: 100%; border-collapse: collapse; }
        .layout-table td { border: none; padding: 0; vertical-align: top; }
        
        /* Kop Surat (Header) */
        .header-section { border-bottom: 2px solid #2563eb; padding-bottom: 15px; margin-bottom: 20px; }
        .company-name { font-size: 24px; font-weight: 900; margin: 0; color: #0f172a; letter-spacing: 1px; }
        .company-sub { font-size: 12px; font-weight: bold; margin: 2px 0 5px 0; color: #2563eb; letter-spacing: 2px; text-transform: uppercase;}
        .company-address { font-size: 10px; color: #64748b; margin: 0; }
        
        /* Info Laporan (Meta) */
        .report-title { font-size: 18px; font-weight: bold; margin: 0 0 10px 0; color: #0f172a; text-transform: uppercase; }
        .meta-info td { font-size: 10px; color: #475569; padding: 3px 0; }
        
        /* Summary Box */
        .summary-box { margin-bottom: 20px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 10px; }
        .summary-box td { text-align: center; border-right: 1px solid #e2e8f0; width: 50%; }
        .summary-box td:last-child { border-right: none; }
        .summary-label { font-size: 10px; color: #64748b; text-transform: uppercase; font-weight: bold; margin-bottom: 5px; }
        .summary-value { font-size: 18px; color: #0f172a; font-weight: bold; }
        .summary-value.highlight { color: #059669; } /* Emerald green */

        /* Main Data Table */
        .data-table { margin-bottom: 30px; }
        .data-table th { 
            background-color: #f1f5f9; 
            color: #475569; 
            font-size: 10px; 
            text-transform: uppercase; 
            padding: 10px 12px; 
            border: 1px solid #cbd5e1;
            border-bottom: 2px solid #94a3b8;
        }
        .data-table td { 
            padding: 10px 12px; 
            border: 1px solid #e2e8f0; 
            font-size: 11px;
            vertical-align: top;
        }
        
        /* 👇 TAMBAHKAN KODE INI DI SINI BOSKU 👇 */
       .data-table {
            page-break-inside: auto;
        }
        .data-table tr, .data-table td {
            page-break-inside: auto; 
        }
        .data-table thead {
            display: table-header-group; 
        }
        
        /* Item List Formatting */
        .item-list { margin: 0; padding: 0; list-style-type: none; }
        .item-list li { margin-bottom: 4px; padding-bottom: 4px; border-bottom: 1px dashed #e2e8f0; }
        .item-list li:last-child { margin-bottom: 0; padding-bottom: 0; border-bottom: none; }
        .qty-badge { font-weight: bold; color: #2563eb; display: inline-block; width: 25px; }

        /* Totals Area */
        .total-row td { background-color: #eff6ff; font-weight: bold; color: #0f172a; border-top: 2px solid #93c5fd; }
        .note-text { font-size: 9px; font-style: italic; margin-top: 4px; display: block; }
        .text-rose { color: #e11d48; }
        
        /* Footer & Signature */
        .footer-section { margin-top: 40px; page-break-inside: avoid; }
        .signature-box { width: 250px; float: right; text-align: center; }
        .signature-date { margin-bottom: 50px; font-size: 11px; }
        .signature-name { font-weight: bold; text-decoration: underline; font-size: 12px; }
        .signature-role { font-size: 10px; color: #64748b; margin-top: 2px; }

        /* Page Numbering */
        footer { position: fixed; bottom: -20px; left: 0px; right: 0px; height: 20px; font-size: 9px; color: #94a3b8; }
        footer .pagenum:before { content: counter(page); }
    </style>
</head>
<body>

    <!-- Footer untuk Nomor Halaman (Harus ditaruh di atas konten body di DomPDF) -->
    <footer>
        <table class="layout-table">
            <tr>
                <td class="text-left">Di-generate oleh DiabloTech System</td>
                <td class="text-right">Halaman <span class="pagenum"></span></td>
            </tr>
        </table>
    </footer>

    <!-- HEADER / KOP SURAT -->
    <div class="header-section">
        <table class="layout-table">
            <tr>
                <td width="55%">
                    <h1 class="company-name">DIABLO<span class="text-blue">TECH</span></h1>
                    <p class="company-sub">PT Aneka Teknologi Solusindo</p>
                    <p class="company-address">
                        Jl. Dipatiukur, Kota Bandung, Jawa Barat<br>
                        Email: admin@anekatech.com | Telp: (022) 1234567<br>
                        Website: www.anekatech.com
                    </p>
                </td>
                <td width="45%" class="text-right">
                    <h2 class="report-title">Laporan Penjualan</h2>
                    <table class="layout-table meta-info" style="float: right; width: 100%;">
                        <tr>
                            <td class="text-right font-bold" width="60%">Kategori Laporan :</td>
                            <td class="text-right" width="40%">{{ $jenis }}</td>
                        </tr>
                        <tr>
                            <td class="text-right font-bold">Periode :</td>
                            <td class="text-right">{{ $periode }}</td>
                        </tr>
                        <tr>
                            <td class="text-right font-bold">Tanggal Cetak :</td>
                            <td class="text-right">{{ date('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- SUMMARY BOX (Ringkasan Cepat) -->
    <div class="summary-box">
        <table class="layout-table">
            <tr>
                <td>
                    <div class="summary-label">Total Transaksi Lunas</div>
                    <div class="summary-value">{{ count($transaksi) }} Pesanan</div>
                </td>
                <td>
                    <div class="summary-label">Grand Total Pendapatan</div>
                    <div class="summary-value highlight">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- MAIN DATA TABLE -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%" class="text-center">Tanggal</th>
                <th width="20%">No. Order</th>
                <th width="40%">Rincian Item Terjual</th>
                <th width="20%" class="text-right">Pendapatan</th>
            </tr>
        </thead>
       <tbody>
            @forelse($transaksi as $index => $trx)
                {{-- Tentukan warna background selang-seling per TRANSAKSI, bukan per baris --}}
                @php $bgColor = $index % 2 == 0 ? '#ffffff' : '#f8fafc'; @endphp
                
                {{-- JIKA TRANSAKSI PUNYA DETAIL BARANG --}}
                @if(count($trx->details) > 0)
                    @foreach($trx->details as $idx => $item)
                        @php
                            $isFirst = $idx === 0;
                            $isLast = $idx === (count($trx->details) - 1);
                        @endphp
                        <tr style="background-color: {{ $bgColor }};">
                            
                            <!-- 1. Kolom No -->
                            <td class="text-center" style="{{ !$isFirst ? 'border-top: none;' : '' }} {{ !$isLast ? 'border-bottom: none;' : '' }}">
                                @if($isFirst) {{ $index + 1 }} @endif
                            </td>
                            
                            <!-- 2. Kolom Tanggal -->
                            <td class="text-center" style="{{ !$isFirst ? 'border-top: none;' : '' }} {{ !$isLast ? 'border-bottom: none;' : '' }}">
                                @if($isFirst) {{ \Carbon\Carbon::parse($trx->created_at)->format('d-m-Y') }} @endif
                            </td>
                            
                            <!-- 3. Kolom No Order -->
                            <td class="font-bold" style="{{ !$isFirst ? 'border-top: none;' : '' }} {{ !$isLast ? 'border-bottom: none;' : '' }}">
                                @if($isFirst) {{ $trx->kode_transaksi ?? 'TRX-'.$trx->id }} @endif
                            </td>
                            
                            <!-- 4. Kolom Rincian Item (Dibuat per baris) -->
                            <td style="border-bottom: none; {{ !$isFirst ? 'border-top: 1px dashed #cbd5e1;' : '' }}">
                                <span class="qty-badge">{{ $item->qty }}x</span> 
                                {{ $item->barang->nama_barang ?? 'Item (Dihapus)' }}
                                
                                {{-- Munculkan info potongan khusus di BARIS TERAKHIR dari item tersebut --}}
                                @if($isLast)
                                    <div style="margin-top: 6px;">
                                        @if($trx->ongkos_kirim > 0)
                                            <span class="note-text text-slate">
                                                * Pendapatan bersih (Telah dipotong ongkir Rp {{ number_format($trx->ongkos_kirim, 0, ',', '.') }})
                                            </span>
                                        @endif

                                        @if($trx->pengajuanRetur && $trx->pengajuanRetur->nominal_refund)
                                            <span class="note-text text-rose">
                                                * Klaim Retur: Dipotong refund senilai Rp {{ number_format($trx->pengajuanRetur->nominal_refund, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            
                            <!-- 5. Kolom Pendapatan -->
                            <td class="text-right font-bold" style="{{ !$isFirst ? 'border-top: none;' : '' }} {{ !$isLast ? 'border-bottom: none;' : '' }}">
                                @if($isFirst) Rp {{ number_format($trx->pendapatan_bersih, 0, ',', '.') }} @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    {{-- JIKA TRANSAKSI KOSONG (Jaga-jaga error) --}}
                    <tr style="background-color: {{ $bgColor }};">
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($trx->created_at)->format('d-m-Y') }}</td>
                        <td class="font-bold">{{ $trx->kode_transaksi ?? 'TRX-'.$trx->id }}</td>
                        <td>Tidak ada rincian item.</td>
                        <td class="text-right font-bold">Rp {{ number_format($trx->pendapatan_bersih, 0, ',', '.') }}</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px; color: #94a3b8;">
                        Tidak ada data transaksi lunas pada periode ini.
                    </td>
                </tr>
            @endforelse
            
            <!-- BARIS TOTAL -->
            @if(count($transaksi) > 0)
            <tr class="total-row">
                <td colspan="4" class="text-right uppercase tracking-widest" style="border-top: 2px solid #93c5fd;">Grand Total Pendapatan Bersih</td>
                <td class="text-right text-blue" style="border-top: 2px solid #93c5fd;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- SIGNATURE AREA -->
  <div class="footer-section">
        <div class="signature-box">
            <div class="signature-date">Bandung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            
            <div style="margin: 20px 0;">
                <div style="display: inline-block; border: 2px solid #059669; color: #059669; padding: 8px 12px; border-radius: 4px; font-weight: 900; font-size: 10px; letter-spacing: 1px; text-transform: uppercase;">
                    VERIFIED BY SYSTEM
                </div>
            </div>
            
            <div class="signature-name">{{ Auth::user()->name ?? 'Administrator' }}</div>
            <div class="signature-role">{{ Auth::user()->role ?? 'Administrator' }}</div>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>