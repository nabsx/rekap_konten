<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px; color: #1e293b;
            padding: 30px;
        }

        /* ── Header ── */
        .pdf-header {
            background: #0f172a;
            color: #fff;
            border-radius: 10px;
            padding: 20px 24px;
            margin-bottom: 20px;
        }
        .pdf-header h1 { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
        .pdf-header p  { font-size: 11px; color: #94a3b8; }
        .pdf-header .grand-total {
            font-size: 28px; font-weight: 700;
            color: #a5b4fc;
        }
        .header-row { display: flex; justify-content: space-between; align-items: flex-start; }

        /* ── Summary table ── */
        .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .summary-table th {
            background: #f1f5f9; text-align: left;
            padding: 8px 12px; font-size: 10px;
            text-transform: uppercase; letter-spacing: .5px;
            color: #64748b; border-bottom: 2px solid #e2e8f0;
        }
        .summary-table td {
            padding: 8px 12px; border-bottom: 1px solid #f1f5f9;
        }
        .summary-table tr:last-child td { border-bottom: none; }
        .summary-table .total-row td { font-weight: 700; background: #f8fafc; }

        /* ── Platform section ── */
        .platform-section { margin-bottom: 20px; page-break-inside: avoid; }
        .platform-header {
            padding: 10px 14px;
            border-radius: 8px 8px 0 0;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .platform-header h3 { font-size: 13px; font-weight: 700; }
        .platform-header .count { font-size: 18px; font-weight: 700; }

        /* ── Detail table ── */
        .detail-table { width: 100%; border-collapse: collapse; }
        .detail-table th {
            background: #f8fafc; text-align: left;
            padding: 7px 10px; font-size: 9.5px;
            text-transform: uppercase; letter-spacing: .4px;
            color: #94a3b8; border-bottom: 1px solid #e2e8f0;
        }
        .detail-table td {
            padding: 7px 10px; border-bottom: 1px solid #f8fafc;
            font-size: 10.5px; vertical-align: top;
        }
        .detail-table tr:last-child td { border-bottom: none; }
        .detail-table .no-data td { text-align: center; color: #94a3b8; font-style: italic; }

        /* ── Footer ── */
        .pdf-footer {
            margin-top: 24px; border-top: 1px solid #e2e8f0;
            padding-top: 12px; color: #94a3b8; font-size: 9.5px;
            display: flex; justify-content: space-between;
        }
    </style>
</head>
<body>

{{-- Header --}}
<div class="pdf-header">
    <div class="header-row">
        <div>
            <h1>Laporan Rekap Konten</h1>
            <p>{{ $monthName }}</p>
            <p style="margin-top:6px;">Digenerate: {{ now()->translatedFormat('d F Y, H:i') }}</p>
        </div>
        <div style="text-align:right;">
            <div style="font-size:10px;color:#94a3b8;">Total Posting</div>
            <div class="grand-total">{{ $recap['grand_total'] }}</div>
        </div>
    </div>
</div>

{{-- Summary Table --}}
<h3 style="font-size:13px;font-weight:700;margin-bottom:10px;color:#0f172a;">Ringkasan Per Platform</h3>
<table class="summary-table">
    <thead>
        <tr>
            <th>Platform</th>
            <th style="text-align:center;">Jumlah Posting</th>
            <th style="text-align:center;">Persentase</th>
        </tr>
    </thead>
    <tbody>
        @foreach($recap['by_platform'] as $item)
        <tr>
            <td><strong>{{ $item['platform']->name }}</strong></td>
            <td style="text-align:center;">{{ $item['total'] }}</td>
            <td style="text-align:center;">
                {{ $recap['grand_total'] > 0 ? round(($item['total'] / $recap['grand_total']) * 100, 1) : 0 }}%
            </td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td>TOTAL</td>
            <td style="text-align:center;">{{ $recap['grand_total'] }}</td>
            <td style="text-align:center;">100%</td>
        </tr>
    </tbody>
</table>

{{-- Detail per platform --}}
<h3 style="font-size:13px;font-weight:700;margin-bottom:12px;color:#0f172a;">Detail Postingan Per Platform</h3>

@foreach($recap['by_platform'] as $item)
<div class="platform-section">
    <div class="platform-header" style="background: {{ $item['platform']->color }};">
        <h3>{{ $item['platform']->name }}</h3>
        <div class="count">{{ $item['total'] }} Posting</div>
    </div>
    <table class="detail-table" style="border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 8px 8px; overflow: hidden;">
        <thead>
            <tr>
                <th style="width:30px;">No</th>
                <th style="width:75px;">Tanggal</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th style="width:80px;">Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse($item['posts'] as $i => $post)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $post->posted_at->format('d/m/Y') }}</td>
                <td><strong>{{ $post->title }}</strong>
                    @if($post->url)<br><span style="color:#6366f1;font-size:9px;">{{ $post->url }}</span>@endif
                </td>
                <td style="color:#475569;">{{ $post->description ? \Str::limit($post->description, 100) : '-' }}</td>
                <td>{{ $post->user->name }}</td>
            </tr>
            @empty
            <tr class="no-data">
                <td colspan="5">Tidak ada postingan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endforeach

{{-- Footer --}}
<div class="pdf-footer">
    <span>RekapKonten — Sistem Rekap Konten</span>
    <span>Laporan {{ $monthName }}</span>
</div>

</body>
</html>
