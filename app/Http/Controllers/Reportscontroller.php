<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::query();

        if ($request->department) $query->where('department', $request->department);
        if ($request->status)     $query->where('status', $request->status);
        if ($request->type)       $query->where('type', $request->type);

        $assets = $query->orderBy('department')->orderBy('type')->get();

        $summary = [
            'total'        => $assets->count(),
            'by_status'    => $assets->groupBy('status')->map->count(),
            'by_type'      => $assets->groupBy('type')->map->count(),
            'by_dept'      => $assets->groupBy('department')->map->count(),
            'deployed'     => $assets->whereIn('status', ['working', 'new'])->whereNotNull('assigned_to')->count(),
        ];

        $departments = Asset::distinct()->orderBy('department')->pluck('department')->filter();

        return view('reports.index', compact('assets', 'summary', 'departments'));
    }
}