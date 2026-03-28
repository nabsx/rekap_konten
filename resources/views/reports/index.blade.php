@extends('layouts.app')

@section('title', 'Rekap Bulanan')
@section('page-title', 'Rekap Bulanan')

@push('styles')
<style>
    .recap-header {
        background: linear-gradient(135deg, #0f172a, #1e3a5f);
        border-radius: 14px; color: #fff; padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .recap-header h4 { font-weight: 800; margin: 0; }
    .platform-recap-card {
        border: 1px solid #e2e8f0; border-radius: 12px;
        overflow: hidden; margin-bottom: 1rem;
    }
    .platform-recap-header {
        display: flex; align-items: center; gap: .75rem;
        padding: .85rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .platform-recap-header .icon-wrap {
        width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
    }
    .total-badge {
        margin-left: auto; font-weight: 700;
        font-size: 1.25rem; line-height: 1;
    }
    .post-list-item {
        display: flex; align-items: flex-start; gap: .75rem;
        padding: .6rem 1.25rem; border-bottom: 1px solid #f8fafc;
        font-size: .85rem;
    }
    .post-list-item:last-child { border-bottom: none; }
    .post-num {
        width: 22px; height: 22px; border-radius: 6px;
        background: #f1f5f9; color: #64748b;
        display: flex; align-items: center; justify-content: center;
        font-size: .7rem; font-weight: 700; flex-shrink: 0; margin-top: 1px;
    }
</style>
@endpush

@section('content')

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('reports.index') }}" class="row g-2 align-items-end">
            <div class="col-6 col-md-3">
                <label class="form-label fw-600" style="font-size:.8rem;">Bulan</label>
                <select name="month" class="form-select form-select-sm rounded-3">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label fw-600" style="font-size:.8rem;">Tahun</label>
                <select name="year" class="form-select form-select-sm rounded-3">
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-6 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm rounded-3">
                    <i class="bi bi-search me-1"></i>Tampilkan Rekap
                </button>
                <a href="{{ route('reports.export.pdf', ['month' => $month, 'year' => $year]) }}"
                   class="btn btn-danger btn-sm rounded-3">
                    <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
                </a>
                <a href="{{ route('reports.export.excel', ['month' => $month, 'year' => $year]) }}"
                   class="btn btn-success btn-sm rounded-3">
                    <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Rekap Header --}}
<div class="recap-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <small style="color:#94a3b8;font-size:.75rem;letter-spacing:.5px;text-transform:uppercase;">Laporan Bulanan</small>
            <h4>{{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }}</h4>
        </div>
        <div class="text-end">
            <div style="font-size:.75rem;color:#94a3b8;">Total Posting</div>
            <div style="font-size:2.5rem;font-weight:800;line-height:1;">{{ $recap['grand_total'] }}</div>
        </div>
    </div>

    {{-- Summary per platform --}}
    <div class="row g-2 mt-2">
        @foreach($recap['by_platform'] as $item)
        <div class="col-4">
            <div style="background:rgba(255,255,255,.08);border-radius:10px;padding:.65rem .9rem;">
                <div style="font-size:.7rem;color:#94a3b8;">{{ $item['platform']->name }}</div>
                <div style="font-size:1.4rem;font-weight:800;">{{ $item['total'] }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Detail per platform --}}
@foreach($recap['by_platform'] as $item)
<div class="platform-recap-card">
    <div class="platform-recap-header" style="background: {{ $item['platform']->color }}08;">
        <div class="icon-wrap" style="background: {{ $item['platform']->color }}1a; color: {{ $item['platform']->color }};">
            <i class="{{ $item['platform']->icon }}"></i>
        </div>
        <div>
            <div class="fw-700" style="color:#0f172a;">{{ $item['platform']->name }}</div>
            <small class="text-muted" style="font-size:.75rem;">Platform</small>
        </div>
        <div class="total-badge" style="color: {{ $item['platform']->color }};">
            {{ $item['total'] }} <small style="font-size:.65rem;font-weight:500;color:#64748b;">posting</small>
        </div>
    </div>

    @if($item['posts']->isEmpty())
        <div class="text-center py-4 text-muted" style="font-size:.875rem;">
            <i class="bi bi-inbox d-block fs-3 mb-1"></i>Tidak ada postingan bulan ini.
        </div>
    @else
        @foreach($item['posts'] as $i => $post)
        <div class="post-list-item">
            <div class="post-num">{{ $i + 1 }}</div>
            <div class="flex-grow-1">
                <div class="fw-600" style="color:#0f172a;">{{ $post->title }}</div>
                @if($post->description)
                    <div class="text-muted" style="font-size:.78rem;">{{ Str::limit($post->description, 80) }}</div>
                @endif
                @if($post->url)
                    <a href="{{ $post->url }}" target="_blank" class="text-primary" style="font-size:.75rem;">
                        <i class="bi bi-link-45deg"></i>{{ Str::limit($post->url, 50) }}
                    </a>
                @endif
            </div>
            <div class="text-muted text-nowrap" style="font-size:.78rem;">
                {{ $post->posted_at->format('d M') }}<br>
                <span>{{ $post->user->name }}</span>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endforeach

@endsection
