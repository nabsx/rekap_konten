<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// ─── Auth ───────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ─── Authenticated routes ────────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard (super_admin & admin)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Posts admin-only CRUD routes (must come before parameterized routes)
    Route::middleware('role:admin')->group(function () {
        Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
        Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
        Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    });

    // Posts view routes - both super_admin and admin can view
    Route::middleware('role:super_admin,admin')->group(function () {
        Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
        Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    });

    // Reports — super_admin & admin
    Route::prefix('reports')->name('reports.')->middleware('role:super_admin,admin')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/export/pdf', [ReportController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/export/excel', [ReportController::class, 'exportExcel'])->name('export.excel');
    });
});

// ─── 403 Fallback ────────────────────────────────────────────────────────────
Route::fallback(function () {
    return redirect()->route('dashboard');
});
