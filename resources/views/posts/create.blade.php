@extends('layouts.app')

@section('title', 'Tambah Postingan')
@section('page-title', 'Tambah Postingan')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">

        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-secondary rounded-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h5 class="fw-700 mb-0" style="color:#0f172a;">Tambah Postingan Baru</h5>
                <small class="text-muted">Isi detail postingan konten</small>
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

                <form action="{{ route('posts.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-600">Platform <span class="text-danger">*</span></label>
                        <select name="platform_id" class="form-select rounded-3 @error('platform_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Platform --</option>
                            @foreach($platforms as $platform)
                                <option value="{{ $platform->id }}" {{ old('platform_id') == $platform->id ? 'selected' : '' }}>
                                    {{ $platform->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('platform_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-600">Tanggal Posting <span class="text-danger">*</span></label>
                        <input type="date" name="posted_at" class="form-control rounded-3 @error('posted_at') is-invalid @enderror"
                               value="{{ old('posted_at', now()->format('Y-m-d')) }}" required>
                        @error('posted_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-600">Judul Postingan <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control rounded-3 @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" placeholder="Masukkan judul postingan..." required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-600">Deskripsi</label>
                        <textarea name="description" class="form-control rounded-3 @error('description') is-invalid @enderror"
                                  rows="4" placeholder="Deskripsi postingan (opsional)...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-600">URL Postingan</label>
                        <input type="url" name="url" class="form-control rounded-3 @error('url') is-invalid @enderror"
                               value="{{ old('url') }}" placeholder="https://...">
                        @error('url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-3">
                            <i class="bi bi-save me-1"></i>Simpan Postingan
                        </button>
                        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary rounded-3">Batal</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
