<?php

namespace App\Exports;

use App\Models\Platform;
use App\Models\Post;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\BorderEdge;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PostsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnWidths, WithEvents
{
    public function __construct(
        private int $year,
        private int $month
    ) {}

    /**
     * Platform ordering: Instagram → TikTok → Facebook → X → YouTube → Website
     */
    private array $platformOrder = [
        'instagram' => 1,
        'tiktok'    => 2,
        'facebook'  => 3,
        'x'         => 4,
        'youtube'   => 5,
        'website'   => 6,
    ];

    public function collection()
    {
        $posts = Post::with('platform', 'user')
            ->whereYear('posted_at', $this->year)
            ->whereMonth('posted_at', $this->month)
            ->get();

        // Sort by platform order, then by date
        return $posts->sort(function ($a, $b) {
            $orderA = $this->platformOrder[$a->platform->slug] ?? 999;
            $orderB = $this->platformOrder[$b->platform->slug] ?? 999;

            if ($orderA !== $orderB) {
                return $orderA <=> $orderB;
            }

            return $a->posted_at <=> $b->posted_at;
        })->values();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Platform',
            'Judul',
            'Deskripsi',
            'URL',
            'Followers',
            'Viewers',
            'Likes',
            'Subscribers',
            'Dibuat Oleh',
            'Catatan',
        ];
    }

    public function map($post): array
    {
        static $i = 0;
        $i++;

        return [
            $i,
            $post->posted_at,  // Will be converted to Excel datetime by AfterSheet
            $post->platform->name,
            $post->title,
            $post->description ?? '',
            $post->url ?? '',
            $post->followers ?? 0,
            $post->viewers ?? 0,
            $post->likes ?? 0,
            $post->subscribers ?? 0,
            $post->user->name ?? '-',
            '',  // Empty catatan column
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,    // No
            'B' => 18,   // Tanggal
            'C' => 14,   // Platform
            'D' => 25,   // Judul
            'E' => 20,   // Deskripsi
            'F' => 25,   // URL
            'G' => 12,   // Followers
            'H' => 12,   // Viewers
            'I' => 10,   // Likes
            'J' => 12,   // Subscribers
            'K' => 15,   // Dibuat Oleh
            'L' => 20,   // Catatan
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row styling
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1e3a5f'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'border' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Get the last row with data
                $highestRow = $sheet->getHighestRow();

                // 1. Format datetime column (B) as Excel datetime format
                for ($row = 2; $row <= $highestRow; $row++) {
                    $cellValue = $sheet->getCell("B{$row}")->getValue();
                    if ($cellValue instanceof \DateTime) {
                        $sheet->getCell("B{$row}")->setValue(
                            Date::dateTimeToExcel($cellValue)
                        );
                        $sheet->getCell("B{$row}")->getStyle()->getNumberFormat()
                            ->setFormatCode('yyyy-mm-dd hh:mm');
                    }
                }

                // 2. Format numeric columns (Followers, Viewers, Likes, Subscribers)
                $numericColumns = ['G', 'H', 'I', 'J'];
                for ($row = 2; $row <= $highestRow; $row++) {
                    foreach ($numericColumns as $col) {
                        $cell = $sheet->getCell("{$col}{$row}");
                        $value = $cell->getValue();
                        
                        // Set numeric format with thousand separator
                        $cell->getStyle()->getNumberFormat()->setFormatCode('#,##0');
                        
                        // Center align numbers
                        $cell->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                }

                // 3. Center align No and Platform columns
                for ($row = 2; $row <= $highestRow; $row++) {
                    $sheet->getCell("A{$row}")->getStyle()->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getCell("C{$row}")->getStyle()->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // 4. Freeze first row (header)
                $sheet->freezePane('A2');

                // 5. Add autofilter to header row
                $sheet->setAutoFilter("A1:L{$highestRow}");

                // 6. Add borders to all data cells
                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CCCCCC'],
                        ],
                    ],
                ];
                $sheet->getStyle("A1:L{$highestRow}")->applyFromArray($styleArray);

                // 7. Set row height for header
                $sheet->getRowDimension(1)->setRowHeight(25);

                // 8. Wrap text for description column
                $sheet->getStyle("E2:E{$highestRow}")->getAlignment()->setWrapText(true);
            },
        ];
    }
}
