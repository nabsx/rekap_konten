<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PostsExport;

class ReportController extends Controller
{
    /**
     * Tampilkan halaman laporan (filter bulan & tahun).
     */
    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year  = $request->get('year', now()->year);

        $recap = $this->getMonthlyRecap($year, $month);

        $availableYears = Post::selectRaw('YEAR(posted_at) as year')
            ->distinct()->orderByDesc('year')->pluck('year')->toArray();

        if (!in_array(now()->year, $availableYears)) {
            $availableYears[] = now()->year;
            rsort($availableYears);
        }

        return view('reports.index', compact('recap', 'month', 'year', 'availableYears'));
    }

    /**
     * Rekap bulanan: group by platform, hitung total, list postingan.
     */
    private function getMonthlyRecap(int $year, int $month): array
    {
        $platforms = Platform::all();
        $recap = [];

        foreach ($platforms as $platform) {
            $posts = Post::with('user')
                ->where('platform_id', $platform->id)
                ->byMonth($year, $month)
                ->orderBy('posted_at')
                ->get();

            $recap[] = [
                'platform' => $platform,
                'total'    => $posts->count(),
                'posts'    => $posts,
            ];
        }

        $allPosts = Post::with('platform', 'user')
            ->byMonth($year, $month)
            ->orderBy('posted_at')
            ->get();

        return [
            'by_platform' => $recap,
            'grand_total' => $allPosts->count(),
            'all_posts'   => $allPosts,
        ];
    }

    /**
     * Export laporan bulanan ke PDF menggunakan DomPDF.
     */
    public function exportPdf(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year  = $request->get('year', now()->year);

        $recap     = $this->getMonthlyRecap($year, $month);
        $monthName = \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y');

        $pdf = Pdf::loadView('reports.pdf', compact('recap', 'month', 'year', 'monthName'))
            ->setPaper('a4', 'portrait');

        $filename = 'laporan-konten-' . $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export laporan bulanan ke Excel.
     */
    public function exportExcel(Request $request)
    {
        $month    = $request->get('month', now()->month);
        $year     = $request->get('year', now()->year);
        $filename = 'laporan-konten-' . $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '.xlsx';

        return Excel::download(new PostsExport($year, $month), $filename);
    }
}
