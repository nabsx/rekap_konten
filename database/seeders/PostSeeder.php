<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Platform;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $platforms = Platform::all();
        $users = User::all();

        $titles = [
            'Strategi Marketing Digital 2024',
            'Tips Produktivitas Kerja Dari Rumah',
            'Panduan Lengkap E-commerce',
            'Tren Teknologi Terbaru',
            'Cara Meningkatkan Engagement',
            'Analisis Pasar Industri Retail',
            'Transformasi Digital Perusahaan',
            'Strategi Branding yang Efektif',
            'Panduan SEO untuk Pemula',
            'Tips Desain Grafis Modern',
            'Strategi Konten untuk Influencer',
            'Analisis Kompetitor Online',
            'Membangun Personal Branding',
            'Strategi Pricing yang Tepat',
            'Teknologi AI untuk Bisnis',
            'Platform Media Sosial Terbaru',
            'Optimasi Conversion Rate',
            'Panduan Email Marketing',
            'Strategi Partnership Bisnis',
            'Analisis Data Pelanggan',
        ];

        $descriptions = [
            'Artikel mendalam tentang strategi marketing digital yang efektif untuk bisnis modern.',
            'Kumpulan tips dan trik untuk meningkatkan produktivitas saat bekerja dari rumah.',
            'Panduan lengkap memulai dan mengembangkan toko e-commerce dari nol.',
            'Update terbaru tentang tren teknologi yang akan mengubah industri.',
            'Cara praktis meningkatkan engagement di media sosial dan platform digital lainnya.',
            'Analisis mendalam tentang kondisi pasar industri retail saat ini.',
            'Panduan transformasi digital untuk perusahaan tradisional menuju era digital.',
            'Strategi branding yang terbukti efektif meningkatkan nilai perusahaan.',
            'Tutorial lengkap SEO untuk pemula yang ingin meningkatkan ranking website.',
            'Panduan desain grafis modern dengan tools dan teknik terkini.',
        ];

        $urls = [
            'https://example.com/post-1',
            'https://example.com/post-2',
            'https://example.com/post-3',
            'https://example.com/post-4',
            'https://example.com/post-5',
        ];

        // Buat 150 posts dengan data dummy
        for ($i = 1; $i <= 150; $i++) {
            $month = rand(1, 12);
            $day = rand(1, 28);
            $year = 2024;

            Post::create([
                'platform_id' => $platforms->random()->id,
                'user_id' => $users->random()->id,
                'title' => $titles[array_rand($titles)] . ' - Post ' . $i,
                'description' => $descriptions[array_rand($descriptions)],
                'url' => $urls[array_rand($urls)] . '/article-' . $i,
                'posted_at' => now()
                    ->setYear($year)
                    ->setMonth($month)
                    ->setDay($day)
                    ->setHour(rand(8, 20))
                    ->setMinute(rand(0, 59)),
            ]);
        }
    }
}
