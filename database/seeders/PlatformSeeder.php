<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Platform;

class PlatformSeeder extends Seeder
{
    public function run(): void
    {
        $platforms = [
            [
                'name'  => 'Instagram',
                'slug'  => 'instagram',
                'icon'  => 'bi-instagram',
                'color' => '#E1306C',
            ],
            [
                'name'  => 'YouTube',
                'slug'  => 'youtube',
                'icon'  => 'bi-youtube',
                'color' => '#FF0000',
            ],
            [
                'name'  => 'Website',
                'slug'  => 'website',
                'icon'  => 'bi-globe',
                'color' => '#0d6efd',
            ],
        ];

        foreach ($platforms as $platform) {
            Platform::updateOrCreate(['slug' => $platform['slug']], $platform);
        }
    }
}
