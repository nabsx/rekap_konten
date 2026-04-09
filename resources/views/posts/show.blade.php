@extends('layouts.app')

@section('title', 'Detail Postingan')
@section('page-title', 'Detail Postingan')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">

        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-secondary rounded-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h5 class="fw-700 mb-0" style="color:#0f172a;">Detail Postingan</h5>
                <small class="text-muted">Informasi lengkap postingan</small>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div>
                        <span class="platform-badge fs-6"
                              style="background: {{ $post->platform->color }}1a; color: {{ $post->platform->color }}; padding: 6px 14px;">
                            <i class="{{ $post->platform->icon }}"></i>
                            {{ $post->platform->name }}
                        </span>
                        @if($post->content_type)
                            <br><small class="text-muted" style="margin-top:4px;display:inline-block;">{{ ucfirst($post->content_type) }}</small>
                        @endif
                    </div>
                    @if(auth()->user()->isAdmin())
                    <div class="d-flex gap-2">
                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-outline-primary rounded-3">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST"
                              onsubmit="return confirm('Hapus postingan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-3">
                                <i class="bi bi-trash me-1"></i>Hapus
                            </button>
                        </form>
                    </div>
                    @endif
                </div>

                <h4 class="fw-700 mb-1" style="color:#0f172a;">{{ $post->title }}</h4>
                <p class="text-muted mb-4" style="font-size:.85rem;">
                    <i class="bi bi-calendar3 me-1"></i>{{ $post->posted_at->translatedFormat('d F Y') }}
                    &nbsp;·&nbsp;
                    <i class="bi bi-person me-1"></i>{{ $post->user->name }}
                    &nbsp;·&nbsp;
                    <i class="bi bi-clock me-1"></i>Dibuat {{ $post->created_at->diffForHumans() }}
                </p>

                @if($post->description)
                <div class="mb-4">
                    <h6 class="fw-700 text-muted mb-2" style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;">Deskripsi</h6>
                    <p style="line-height:1.7;color:#334155;">{{ $post->description }}</p>
                </div>
                @endif

                @if($post->url)
                <div class="mb-4">
                    <h6 class="fw-700 text-muted mb-2" style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;">URL</h6>
                    <a href="{{ $post->url }}" target="_blank" class="text-primary" style="font-size:.875rem;">
                        <i class="bi bi-link-45deg me-1"></i>{{ $post->url }}
                    </a>
                </div>
                @endif

                @if($post->followers || $post->viewers || $post->likes || $post->subscribers)
                <div class="mt-4 pt-4 border-top">
                    <h6 class="fw-700 text-muted mb-3" style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;">Metrik Konten</h6>
                    <div class="row g-3">
                        @if($post->followers)
                        <div class="col-6 col-md-3">
                            <div class="text-center p-3" style="background:#f1f5f9;border-radius:8px;">
                                <div style="font-size:1.5rem;color:#0d6efd;">👥</div>
                                <div class="fw-700" style="font-size:1.25rem;">{{ number_format($post->followers) }}</div>
                                <small class="text-muted">Pengikut</small>
                            </div>
                        </div>
                        @endif

                        @if($post->viewers)
                        <div class="col-6 col-md-3">
                            <div class="text-center p-3" style="background:#f1f5f9;border-radius:8px;">
                                <div style="font-size:1.5rem;color:#0d6efd;">👁️</div>
                                <div class="fw-700" style="font-size:1.25rem;">{{ number_format($post->viewers) }}</div>
                                <small class="text-muted">Penonton</small>
                            </div>
                        </div>
                        @endif

                        @if($post->likes)
                        <div class="col-6 col-md-3">
                            <div class="text-center p-3" style="background:#f1f5f9;border-radius:8px;">
                                <div style="font-size:1.5rem;color:#0d6efd;">❤️</div>
                                <div class="fw-700" style="font-size:1.25rem;">{{ number_format($post->likes) }}</div>
                                <small class="text-muted">Likes</small>
                            </div>
                        </div>
                        @endif

                        @if($post->subscribers)
                        <div class="col-6 col-md-3">
                            <div class="text-center p-3" style="background:#f1f5f9;border-radius:8px;">
                                <div style="font-size:1.5rem;color:#0d6efd;">📢</div>
                                <div class="fw-700" style="font-size:1.25rem;">{{ number_format($post->subscribers) }}</div>
                                <small class="text-muted">Pelanggan</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
