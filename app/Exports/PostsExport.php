<?php

namespace App\Exports;

use App\Models\Platform;
use App\Models\Post;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PostsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function __construct(
        private int $year,
        private int $month
    ) {}

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
            $post->posted_at->format('d/m/Y H:i'),
            $post->platform->name,
            $post->title,
            $post->description ?? '',
            $post->url ?? '',
            $post->user->name ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1e3a5f']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }
}
