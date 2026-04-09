@extends('layouts.app')

@section('title', 'Daftar Postingan')
@section('page-title', 'Daftar Postingan')

@section('content')

{{-- Header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-700 mb-0" style="color:#0f172a;">Postingan Konten</h5>
        <small class="text-muted">Kelola semua postingan platform</small>
    </div>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('posts.create') }}" class="btn btn-primary rounded-3 d-flex align-items-center gap-1">
        <i class="bi bi-plus-circle"></i> Tambah Postingan
    </a>
    @endif
</div>

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('posts.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-3">
                <label class="form-label fw-600" style="font-size:.8rem;">Cari Judul</label>
                <input type="text" name="search" class="form-control form-control-sm rounded-3"
                       value="{{ request('search') }}" placeholder="Cari postingan...">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label fw-600" style="font-size:.8rem;">Platform</label>
                <select name="platform_id" class="form-select form-select-sm rounded-3">
                    <option value="">Semua</option>
                    @foreach($platforms as $p)
                        <option value="{{ $p->id }}" {{ request('platform_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label fw-600" style="font-size:.8rem;">Bulan</label>
                <select name="month" class="form-select form-select-sm rounded-3">
                    <option value="">Semua</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label fw-600" style="font-size:.8rem;">Tahun</label>
                <select name="year" class="form-select form-select-sm rounded-3">
                    <option value="">Semua</option>
                    @for($y = now()->year; $y >= now()->year - 3; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-6 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm rounded-3 flex-grow-1">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary btn-sm rounded-3">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between py-3">
        <span><i class="bi bi-table me-1"></i>{{ $posts->total() }} Postingan Ditemukan</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Judul</th>
                        <th>Platform</th>
                        <th>Tanggal</th>
                        <th>Dibuat Oleh</th>
                        <th style="width:140px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $index => $post)
                    <tr>
                        <td class="text-muted">{{ $posts->firstItem() + $index }}</td>
                        <td>
                            <div class="fw-600" style="font-size:.875rem;">{{ Str::limit($post->title, 50) }}</div>
                            @if($post->description)
                                <small class="text-muted">{{ Str::limit($post->description, 60) }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="platform-badge"
                                  style="background: {{ $post->platform->color }}1a; color: {{ $post->platform->color }};">
                                <i class="{{ $post->platform->icon }}"></i>
                                {{ $post->platform->name }}
                            </span>
                        </td>
                        <td style="font-size:.85rem;">{{ $post->posted_at->format('d M Y') }}</td>
                        <td style="font-size:.85rem;">{{ $post->user->name }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-outline-secondary rounded-2" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(auth()->user()->isAdmin())
                                <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-outline-primary rounded-2" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('posts.destroy', $post) }}" method="POST"
                                      onsubmit="return confirm('Hapus postingan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-2" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Belum ada postingan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($posts->hasPages())
    <div class="card-footer bg-white border-top py-3">
        {{ $posts->links() }}
    </div>
    @endif
</div>

@endsection
