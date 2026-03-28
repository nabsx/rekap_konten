<?php

namespace App\Exports;

use App\Models\Platform;
use App\Models\Post;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Maatwebsite\Excel\Concerns\WithTitle as WithTitleContract;

class PostsExport implements WithMultipleSheets
{
    public function __construct(
        private int $year,
        private int $month
    ) {}

    public function sheets(): array
    {
        $sheets    = [];
        $platforms = Platform::all();

        // Sheet 1: Ringkasan semua platform
        $sheets[] = new PostsSummarySheet($this->year, $this->month);

        // Sheet per platform
        foreach ($platforms as $platform) {
            $sheets[] = new PostsPlatformSheet($this->year, $this->month, $platform);
        }

        return $sheets;
    }
}

// ────────────────────────────────────────────────────────────────────────────

class PostsSummarySheet implements
    FromCollection, WithHeadings, WithMapping,
    WithTitle, WithStyles, ShouldAutoSize
{
    public function __construct(
        private int $year,
        private int $month
    ) {}

    public function title(): string
    {
        return 'Ringkasan';
    }

    public function collection()
    {
        return Post::with('platform', 'user')
            ->whereYear('posted_at', $this->year)
            ->whereMonth('posted_at', $this->month)
            ->orderBy('posted_at')
            ->get();
    }

    public function headings(): array
    {
        return ['No', 'Tanggal', 'Platform', 'Judul', 'Deskripsi', 'URL', 'Dibuat Oleh'];
    }

    public function map($post): array
    {
        static $i = 0;
        $i++;
        return [
            $i,
            $post->posted_at->format('d/m/Y'),
            $post->platform->name,
            $post->title,
            $post->description,
            $post->url,
            $post->user->name,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1e3a5f']],
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            ],
        ];
    }
}

// ────────────────────────────────────────────────────────────────────────────

class PostsPlatformSheet implements
    FromCollection, WithHeadings, WithMapping,
    WithTitleContract, WithStyles, ShouldAutoSize
{
    public function __construct(
        private int $year,
        private int $month,
        private \App\Models\Platform $platform
    ) {}

    public function title(): string
    {
        return $this->platform->name;
    }

    public function collection()
    {
        return Post::with('user')
            ->where('platform_id', $this->platform->id)
            ->whereYear('posted_at', $this->year)
            ->whereMonth('posted_at', $this->month)
            ->orderBy('posted_at')
            ->get();
    }

    public function headings(): array
    {
        return ['No', 'Tanggal', 'Judul', 'Deskripsi', 'URL', 'Dibuat Oleh'];
    }

    public function map($post): array
    {
        static $i = 0;
        $i++;
        return [
            $i,
            $post->posted_at->format('d/m/Y'),
            $post->title,
            $post->description,
            $post->url,
            $post->user->name,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => ltrim($this->platform->color, '#')]],
            ],
        ];
    }
}
