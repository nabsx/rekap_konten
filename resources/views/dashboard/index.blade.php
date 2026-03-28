@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .stat-icon.instagram { background: rgba(225,48,108,.12); color: #E1306C; }
    .stat-icon.youtube   { background: rgba(255,0,0,.1);    color: #FF0000; }
    .stat-icon.website   { background: rgba(13,110,253,.1); color: #0d6efd; }
    .stat-icon.total     { background: rgba(99,102,241,.12);color: #6366f1; }
    .chart-card { border-radius: 14px; border: 1px solid #e2e8f0; background: #fff; padding: 1.25rem; }
    .chart-card h6 { font-weight: 700; color: #0f172a; font-size: .875rem; margin-bottom: 1rem; }
    .recent-post-item {
        display: flex; align-items: center; gap: .85rem;
        padding: .65rem 0; border-bottom: 1px solid #f1f5f9;
    }
    .recent-post-item:last-child { border-bottom: none; }
    .post-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
    .year-filter select { font-size: .85rem; border-radius: 8px; border: 1.5px solid #e2e8f0; }
</style>
@endpush

@section('content')

{{-- Year filter --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-700 mb-0" style="color:#0f172a;">Statistik Konten</h5>
        <small class="text-muted">Ringkasan aktivitas posting</small>
    </div>
    <form method="GET" action="{{ route('dashboard') }}" class="year-filter d-flex align-items-center gap-2">
        <label class="text-muted" style="font-size:.82rem;">Tahun:</label>
        <select name="year" class="form-select form-select-sm" onchange="this.form.submit()">
            @foreach($availableYears as $y)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
    </form>
</div>

{{-- Stat cards --}}
<div class="row g-3 mb-4">
    {{-- Total this month --}}
    <div class="col-6 col-md-3">
        <div class="stat-card h-100">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="stat-label mb-1">Posting Bulan Ini</div>
                    <div class="stat-number">{{ $totalThisMonth }}</div>
                </div>
                <div class="stat-icon total"><i class="bi bi-calendar-check"></i></div>
            </div>
        </div>
    </div>

    {{-- Total this year --}}
    <div class="col-6 col-md-3">
        <div class="stat-card h-100">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="stat-label mb-1">Total Tahun {{ $year }}</div>
                    <div class="stat-number">{{ $totalThisYear }}</div>
                </div>
                <div class="stat-icon total"><i class="bi bi-graph-up"></i></div>
            </div>
        </div>
    </div>

    {{-- Per platform --}}
    @foreach($platforms as $platform)
    <div class="col-6 col-md-3">
        <div class="stat-card h-100">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="stat-label mb-1">{{ $platform->name }}</div>
                    <div class="stat-number">{{ $platform->posts_count }}</div>
                    <small class="text-muted" style="font-size:.72rem;">total postingan</small>
                </div>
                <div class="stat-icon {{ $platform->slug }}" style="background: {{ $platform->color }}1a; color: {{ $platform->color }};">
                    <i class="{{ $platform->icon }}"></i>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Charts row --}}
<div class="row g-3 mb-4">
    {{-- Line chart: posting per month --}}
    <div class="col-12 col-lg-8">
        <div class="chart-card h-100">
            <h6><i class="bi bi-bar-chart me-1"></i>Jumlah Posting Per Bulan — {{ $year }}</h6>
            <canvas id="monthlyChart" height="90"></canvas>
        </div>
    </div>

    {{-- Doughnut: per platform --}}
    <div class="col-12 col-lg-4">
        <div class="chart-card h-100">
            <h6><i class="bi bi-pie-chart me-1"></i>Distribusi Per Platform ({{ $year }})</h6>
            <canvas id="platformChart" height="200"></canvas>
            <div class="mt-3">
                @foreach($chartData['platformNames'] as $i => $name)
                <div class="d-flex align-items-center justify-content-between mb-1" style="font-size:.8rem;">
                    <span class="d-flex align-items-center gap-2">
                        <span style="width:10px;height:10px;border-radius:50%;background:{{ $chartData['platformColors'][$i] }};display:inline-block;"></span>
                        {{ $name }}
                    </span>
                    <strong>{{ $chartData['platformTotals'][$i] }}</strong>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Grouped bar chart per platform --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="chart-card">
            <h6><i class="bi bi-bar-chart-steps me-1"></i>Posting Per Platform Per Bulan — {{ $year }}</h6>
            <canvas id="groupedChart" height="70"></canvas>
        </div>
    </div>
</div>

{{-- Recent posts --}}
<div class="row g-3">
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between py-3">
                <span><i class="bi bi-clock-history me-1"></i>Postingan Terbaru</span>
                @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-primary rounded-pill" style="font-size:.75rem;">Lihat Semua</a>
                @endif
            </div>
            <div class="card-body py-2">
                @forelse($recentPosts as $post)
                <div class="recent-post-item">
                    <span class="post-dot" style="background:{{ $post->platform->color }}"></span>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="fw-600 text-truncate" style="font-size:.85rem;max-width:220px;">{{ $post->title }}</div>
                        <small class="text-muted">{{ $post->platform->name }} · {{ $post->posted_at->format('d M Y') }}</small>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center py-3" style="font-size:.875rem;">Belum ada postingan.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="card h-100">
            <div class="card-header py-3">
                <i class="bi bi-lightning-charge me-1"></i>Aksi Cepat
            </div>
            <div class="card-body d-flex flex-column gap-2 justify-content-center">
                @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('posts.create') }}" class="btn btn-primary rounded-3">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Postingan Baru
                </a>
                @endif
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary rounded-3">
                    <i class="bi bi-bar-chart-line me-1"></i>Lihat Rekap Bulanan
                </a>
                <a href="{{ route('reports.export.pdf', ['month' => now()->month, 'year' => now()->year]) }}" class="btn btn-outline-danger rounded-3">
                    <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF Bulan Ini
                </a>
                <a href="{{ route('reports.export.excel', ['month' => now()->month, 'year' => now()->year]) }}" class="btn btn-outline-success rounded-3">
                    <i class="bi bi-file-earmark-excel me-1"></i>Export Excel Bulan Ini
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
const labels       = @json($chartData['labels']);
const monthlyData  = @json($chartData['monthlyTotals']);
const platformData = @json($chartData['platformData']);

// Default chart font
Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
Chart.defaults.font.size   = 12;

// ── 1. Monthly line/bar chart ──
new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels,
        datasets: [{
            label: 'Total Posting',
            data: monthlyData,
            backgroundColor: 'rgba(99,102,241,.2)',
            borderColor: '#6366f1',
            borderWidth: 2,
            borderRadius: 6,
            tension: .4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
            x: { grid: { display: false } }
        }
    }
});

// ── 2. Doughnut chart per platform ──
new Chart(document.getElementById('platformChart'), {
    type: 'doughnut',
    data: {
        labels: @json($chartData['platformNames']),
        datasets: [{
            data: @json($chartData['platformTotals']),
            backgroundColor: @json($chartData['platformColors']),
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw} posting` } }
        }
    }
});

// ── 3. Grouped bar chart per platform per month ──
new Chart(document.getElementById('groupedChart'), {
    type: 'bar',
    data: {
        labels,
        datasets: platformData.map(d => ({
            ...d,
            borderRadius: 5,
            borderWidth: 0,
            backgroundColor: d.backgroundColor + 'cc',
        }))
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top', labels: { boxWidth: 12, padding: 16 } } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush
