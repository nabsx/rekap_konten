@extends('layouts.app')

@section('title', 'Edit Postingan')
@section('page-title', 'Edit Postingan')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">

        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-secondary rounded-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h5 class="fw-700 mb-0" style="color:#0f172a;">Edit Postingan</h5>
                <small class="text-muted">Perbarui data postingan</small>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger rounded-3 border-0">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li style="font-size:.875rem;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('posts.update', $post) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-600">Platform <span class="text-danger">*</span></label>
                        <select name="platform_id" id="platformSelect" class="form-select rounded-3 @error('platform_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Platform --</option>
                            @foreach($platforms as $platform)
                                <option value="{{ $platform->id }}" data-slug="{{ $platform->slug }}"
                                    {{ old('platform_id', $post->platform_id) == $platform->id ? 'selected' : '' }}>
                                    {{ $platform->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('platform_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Content Type Field (Instagram Only) --}}
                    <div class="mb-3" id="contentTypeField" style="display: none;">
                        <label class="form-label fw-600">Tipe Konten <span class="text-danger">*</span></label>
                        <select name="content_type" id="contentType" class="form-select rounded-3 @error('content_type') is-invalid @enderror">
                            <option value="">-- Pilih Tipe Konten --</option>
                            <option value="reels" {{ old('content_type', $post->content_type) == 'reels' ? 'selected' : '' }}>Reels</option>
                            <option value="post" {{ old('content_type', $post->content_type) == 'post' ? 'selected' : '' }}>Post</option>
                        </select>
                        @error('content_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Followers Field --}}
                    <div class="mb-3" id="followersField" style="display: none;">
                        <label class="form-label fw-600">Pengikut</label>
                        <input type="number" name="followers" id="followers" class="form-control rounded-3 @error('followers') is-invalid @enderror"
                               value="{{ old('followers', $post->followers) }}" min="0" placeholder="Jumlah pengikut">
                        @error('followers')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Viewers Field --}}
                    <div class="mb-3" id="viewersField" style="display: none;">
                        <label class="form-label fw-600">Penonton</label>
                        <input type="number" name="viewers" id="viewers" class="form-control rounded-3 @error('viewers') is-invalid @enderror"
                               value="{{ old('viewers', $post->viewers) }}" min="0" placeholder="Jumlah penonton">
                        @error('viewers')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Likes Field --}}
                    <div class="mb-3" id="likesField" style="display: none;">
                        <label class="form-label fw-600">Likes</label>
                        <input type="number" name="likes" id="likes" class="form-control rounded-3 @error('likes') is-invalid @enderror"
                               value="{{ old('likes', $post->likes) }}" min="0" placeholder="Jumlah likes">
                        @error('likes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Subscribers Field (YouTube Only) --}}
                    <div class="mb-3" id="subscribersField" style="display: none;">
                        <label class="form-label fw-600">Pelanggan</label>
                        <input type="number" name="subscribers" id="subscribers" class="form-control rounded-3 @error('subscribers') is-invalid @enderror"
                               value="{{ old('subscribers', $post->subscribers) }}" min="0" placeholder="Jumlah pelanggan">
                        @error('subscribers')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-600">Tanggal Posting <span class="text-danger">*</span></label>
                        <input type="date" name="posted_at" class="form-control rounded-3 @error('posted_at') is-invalid @enderror"
                               value="{{ old('posted_at', $post->posted_at->format('Y-m-d')) }}" required>
                        @error('posted_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-600">Judul Postingan <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control rounded-3 @error('title') is-invalid @enderror"
                               value="{{ old('title', $post->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-600">Deskripsi</label>
                        <textarea name="description" class="form-control rounded-3 @error('description') is-invalid @enderror"
                                  rows="4">{{ old('description', $post->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-600">URL Postingan</label>
                        <input type="url" name="url" class="form-control rounded-3 @error('url') is-invalid @enderror"
                               value="{{ old('url', $post->url) }}" placeholder="https://...">
                        @error('url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-3">
                            <i class="bi bi-save me-1"></i>Perbarui Postingan
                        </button>
                        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary rounded-3">Batal</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const platformSelect = document.getElementById('platformSelect');
    const contentTypeField = document.getElementById('contentTypeField');
    const followersField = document.getElementById('followersField');
    const viewersField = document.getElementById('viewersField');
    const likesField = document.getElementById('likesField');
    const subscribersField = document.getElementById('subscribersField');

    function updateFieldsVisibility() {
        const selectedOption = platformSelect.options[platformSelect.selectedIndex];
        const slug = selectedOption.getAttribute('data-slug');

        // Hide all fields first
        contentTypeField.style.display = 'none';
        followersField.style.display = 'none';
        viewersField.style.display = 'none';
        likesField.style.display = 'none';
        subscribersField.style.display = 'none';

        // Clear requirements
        document.getElementById('contentType').required = false;

        // Show fields based on platform
        if (slug === 'instagram') {
            contentTypeField.style.display = 'block';
            followersField.style.display = 'block';
            viewersField.style.display = 'block';
            likesField.style.display = 'block';
            document.getElementById('contentType').required = true;
        } else if (slug === 'tiktok' || slug === 'x' || slug === 'facebook') {
            followersField.style.display = 'block';
            viewersField.style.display = 'block';
            likesField.style.display = 'block';
        } else if (slug === 'youtube') {
            viewersField.style.display = 'block';
            likesField.style.display = 'block';
            subscribersField.style.display = 'block';
        }
        // website: no additional fields
    }

    platformSelect.addEventListener('change', updateFieldsVisibility);
    updateFieldsVisibility(); // Initial call on page load
});
</script>
@endsection
