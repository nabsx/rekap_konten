<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);

        // Total postings per platform (all time)
        $platforms = Platform::withCount('posts')->get();

        // Posts per month per platform for the selected year (for Chart.js)
        $chartData = $this->getChartData($year);

        // Recent posts (last 5)
        $recentPosts = Post::with('platform', 'user')
            ->orderByDesc('posted_at')
            ->limit(5)
            ->get();

        // Total posts this month
        $totalThisMonth = Post::byMonth(now()->year, now()->month)->count();

        // Total posts this year
        $totalThisYear = Post::byYear($year)->count();

        // Available years for filter
        $availableYears = Post::selectRaw('YEAR(posted_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        if (!in_array(now()->year, $availableYears)) {
            $availableYears[] = now()->year;
            rsort($availableYears);
        }

        return view('dashboard.index', compact(
            'platforms',
            'chartData',
            'recentPosts',
            'totalThisMonth',
            'totalThisYear',
            'year',
            'availableYears'
        ));
    }

    private function getChartData(int $year): array
    {
        $platforms = Platform::all();
        $months    = range(1, 12);

        // Posts per month (total all platforms)
        $monthlyTotals = Post::selectRaw('MONTH(posted_at) as month, COUNT(*) as total')
            ->whereYear('posted_at', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Posts per month per platform
        $platformData = [];
        foreach ($platforms as $platform) {
            $data = Post::selectRaw('MONTH(posted_at) as month, COUNT(*) as total')
                ->where('platform_id', $platform->id)
                ->whereYear('posted_at', $year)
                ->groupBy('month')
                ->pluck('total', 'month')
                ->toArray();

            $platformData[] = [
                'label'           => $platform->name,
                'data'            => array_map(fn($m) => $data[$m] ?? 0, $months),
                'backgroundColor' => $platform->color,
                'borderColor'     => $platform->color,
            ];
        }

        return [
            'labels'        => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            'monthlyTotals' => array_map(fn($m) => $monthlyTotals[$m] ?? 0, $months),
            'platformData'  => $platformData,
            'platformNames' => $platforms->pluck('name')->toArray(),
            'platformTotals'=> $platforms->map(fn($p) => Post::where('platform_id', $p->id)->whereYear('posted_at', $year)->count())->toArray(),
            'platformColors'=> $platforms->pluck('color')->toArray(),
        ];
    }
}
