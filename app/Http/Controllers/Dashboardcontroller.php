<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = Asset::selectRaw('status, count(*) as total')
            ->groupBy('status')->pluck('total', 'status');

        $typeCounts = Asset::selectRaw('type, count(*) as total')
            ->groupBy('type')->pluck('total', 'type');

        $deptCounts = Asset::whereNotNull('department')
            ->selectRaw('department, count(*) as total')
            ->groupBy('department')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $recentAssets = Asset::orderBy('created_at', 'desc')->limit(8)->get();

        $total = Asset::count();
        $deployedCount = Asset::whereIn('status', ['working', 'new'])
            ->whereNotNull('assigned_to')->count();

        return view('dashboard', compact(
            'stats', 'typeCounts', 'deptCounts',
            'recentAssets', 'total', 'deployedCount'
        ));
    }
}