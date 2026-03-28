# RekapKonten — Sistem Rekap Postingan Konten

Aplikasi Laravel 11 untuk merekap postingan konten di berbagai platform (Instagram, YouTube, Website) dengan fitur autentikasi berbasis role, dashboard statistik, rekap bulanan, dan export laporan PDF/Excel.

---

## Stack Teknologi

| Komponen         | Detail                       |
| ---------------- | ---------------------------- |
| **Framework**    | Laravel 11                   |
| **Database**     | MySQL                        |
| **Frontend**     | Laravel Blade + Bootstrap 5  |
| **Charts**       | Chart.js 4                   |
| **PDF Export**   | barryvdh/laravel-dompdf ^3.0 |
| **Excel Export** | maatwebsite/excel ^3.1       |
| **Icons**        | Bootstrap Icons 1.11         |
| **Font**         | Plus Jakarta Sans            |

---

## Struktur File

```
app/
├── Exports/
│   └── PostsExport.php           ← Excel export (multi-sheet)
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php    ← Login & Logout
│   │   ├── DashboardController.php
│   │   ├── PostController.php    ← CRUD Postingan
│   │   └── ReportController.php  ← Rekap + Export PDF/Excel
│   └── Middleware/
│       └── RoleMiddleware.php    ← role:super_admin,admin
├── Models/
│   ├── Platform.php
│   ├── Post.php                  ← SoftDeletes, scope byMonth/byYear
│   └── User.php

bootstrap/
└── app.php                       ← Registrasi alias middleware 'role'

database/
├── migrations/
│   ├── ..._create_users_table.php
│   ├── ..._create_platforms_table.php
│   └── ..._create_posts_table.php
└── seeders/
    ├── DatabaseSeeder.php
    ├── PlatformSeeder.php        ← Instagram, YouTube, Website
    └── UserSeeder.php            ← super_admin & admin

resources/views/
├── auth/
│   └── login.blade.php
├── layouts/
│   └── app.blade.php             ← Layout utama dengan sidebar
├── dashboard/
│   └── index.blade.php           ← Chart.js: bar, doughnut, grouped bar
├── posts/
│   ├── index.blade.php           ← List + filter + pagination
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── show.blade.php
└── reports/
    ├── index.blade.php           ← Rekap bulanan per platform
    └── pdf.blade.php             ← Template PDF (DomPDF)

routes/
└── web.php
```

---

## Cara Instalasi

### 1. Buat project Laravel baru

```bash
composer create-project laravel/laravel RekapKonten
cd RekapKonten
```

### 2. Install dependencies tambahan

```bash
composer require barryvdh/laravel-dompdf
composer require maatwebsite/excel
```

### 3. Copy semua file dari paket ini ke project

Salin semua file sesuai struktur di atas ke dalam project Anda.

### 4. Konfigurasi environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:

```env
DB_DATABASE=RekapKonten
DB_USERNAME=root
DB_PASSWORD=your_password

APP_LOCALE=id
APP_TIMEZONE=Asia/Jakarta
```

### 5. Konfigurasi locale Indonesia (di config/app.php)

```php
'locale' => 'id',
'timezone' => 'Asia/Jakarta',
```

Install locale Carbon Indonesia:

```bash
# Carbon sudah ada di Laravel, tapi untuk translasi:
# Tambahkan di AppServiceProvider::boot():
\Carbon\Carbon::setLocale('id');
```

### 6. Publish config Excel (opsional)

```bash
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config
```

### 7. Buat database dan jalankan migration

```bash
mysql -u root -p -e "CREATE DATABASE RekapKonten CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate
php artisan db:seed
```

### 8. Jalankan server

```bash
php artisan serve
```

Akses di: **http://localhost:8000**

---

## Akun Login

| Role        | Email                  | Password |
| ----------- | ---------------------- | -------- |
| Super Admin | superadmin@example.com | password |
| Admin       | admin@example.com      | password |

---

## Fitur Per Role

### Super Admin

-   ✅ Dashboard statistik + Chart.js
-   ✅ CRUD postingan (tambah, edit, hapus, lihat)
-   ✅ Filter postingan (platform, bulan, tahun, judul)
-   ✅ Rekap bulanan per platform
-   ✅ Export PDF (DomPDF) — multi-platform dengan detail
-   ✅ Export Excel (Maatwebsite) — multi-sheet per platform

### Admin

-   ✅ Dashboard statistik + Chart.js
-   ✅ Rekap bulanan per platform
-   ✅ Export PDF & Excel
-   ❌ Tidak bisa CRUD postingan

---

## Rekap Bulanan — Logika

```php
// ReportController::getMonthlyRecap()
foreach ($platforms as $platform) {
    $posts = Post::where('platform_id', $platform->id)
        ->byMonth($year, $month)   // scope di Post model
        ->orderBy('posted_at')
        ->get();

    $recap[] = [
        'platform' => $platform,
        'total'    => $posts->count(),
        'posts'    => $posts,
    ];
}
```

---

## Chart.js — Grafik

1. **Bar Chart** — Total posting per bulan (1 tahun)
2. **Doughnut Chart** — Distribusi posting per platform
3. **Grouped Bar Chart** — Posting per platform per bulan

---

## Export PDF

Menggunakan **barryvdh/laravel-dompdf**:

-   Header laporan dengan bulan & grand total
-   Tabel ringkasan per platform + persentase
-   Detail postingan per platform (judul, tanggal, deskripsi, URL)

## Export Excel

Menggunakan **maatwebsite/excel** dengan **multiple sheets**:

-   Sheet 1: **Ringkasan** (semua platform)
-   Sheet 2+: **Per platform** (Instagram, YouTube, Website)

---

## Notes Penting

-   `Post` model menggunakan **SoftDeletes** (data tidak benar-benar terhapus)
-   Middleware `role` didaftarkan di `bootstrap/app.php` (Laravel 11 style)
-   Semua filter menggunakan **Eloquent scopes** (`byMonth`, `byYear`)
-   Carbon locale diset ke `id` untuk format tanggal Bahasa Indonesia
