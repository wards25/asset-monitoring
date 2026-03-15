@extends('layouts.app')
@section('title', 'Dashboard — AssetTrack')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Dashboard</div>
        <div class="page-subtitle">IT Asset Monitoring Overview — {{ now()->format('l, F d Y') }}</div>
    </div>
    <div class="page-actions">
        <a href="{{ route('assets.create') }}" class="btn btn-primary">
            <svg viewBox="0 0 20 20" fill="none"><path d="M10 4v12M4 10h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Add Asset
        </a>
        <a href="{{ route('assets.scan') }}" class="btn btn-outline">
            <svg viewBox="0 0 20 20" fill="none"><path d="M2 7V4a2 2 0 012-2h3M13 2h3a2 2 0 012 2v3M18 13v3a2 2 0 01-2 2h-3M7 18H4a2 2 0 01-2-2v-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M6 10h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Scan
        </a>
    </div>
</div>

<!-- Summary Stats -->
<div class="stats-grid">
    <div class="stat-card total">
        <div class="stat-num">{{ $total }}</div>
        <div class="stat-label">Total Assets</div>
        <div class="stat-type">All items</div>
    </div>
    <div class="stat-card working">
        <div class="stat-num">{{ $deployedCount }}</div>
        <div class="stat-label">Deployed</div>
        <div class="stat-type">Assigned units</div>
    </div>
    @foreach(\App\Models\Asset::STATUSES as $key => $label)
    <div class="stat-card {{ $key }}">
        <div class="stat-num">{{ $stats[$key] ?? 0 }}</div>
        <div class="stat-label">{{ $label }}</div>
        <div class="stat-type">{{ ucfirst($key) }} units</div>
    </div>
    @endforeach
</div>

<div class="dash-grid">
    <!-- Assets by Type -->
    <div class="chart-card">
        <div class="chart-card-header">
            <div class="chart-card-title">ASSETS BY TYPE</div>
        </div>
        <div class="chart-card-body">
            <div class="type-bars">
                @php $maxType = max(array_values($typeCounts->toArray() ?: [1])); @endphp
                @foreach(\App\Models\Asset::TYPES as $key => $label)
                @php $count = $typeCounts[$key] ?? 0; $pct = $maxType > 0 ? round($count / $maxType * 100) : 0; @endphp
                <div class="type-bar-row">
                    <div class="type-bar-label">{{ $label }}</div>
                    <div class="type-bar-track">
                        <div class="type-bar-fill" data-width="{{ $pct }}" style="width:0"></div>
                    </div>
                    <div class="type-bar-count">{{ $count }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Assets by Department -->
    <div class="chart-card">
        <div class="chart-card-header">
            <div class="chart-card-title">TOP DEPARTMENTS</div>
        </div>
        <div class="chart-card-body">
            <div class="type-bars">
                @php $maxDept = $deptCounts->max('total') ?: 1; @endphp
                @foreach($deptCounts as $d)
                @php $pct = round($d->total / $maxDept * 100); @endphp
                <div class="type-bar-row">
                    <div class="type-bar-label">{{ $d->department }}</div>
                    <div class="type-bar-track">
                        <div class="type-bar-fill" data-width="{{ $pct }}" style="width:0; background: var(--working)"></div>
                    </div>
                    <div class="type-bar-count">{{ $d->total }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Recent Assets -->
<div class="chart-card">
    <div class="chart-card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <div class="chart-card-title">RECENTLY ADDED ASSETS</div>
        <a href="{{ route('assets.index') }}" class="btn btn-sm btn-outline">View All</a>
    </div>
    <div class="table-wrapper" style="border:none;border-radius:0;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Sticker No</th>
                    <th>Type</th>
                    <th>Brand / Model</th>
                    <th>Status</th>
                    <th>Department</th>
                    <th>Assigned To</th>
                    <th>Date Added</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentAssets as $asset)
                <tr>
                    <td><a href="{{ route('assets.show', $asset) }}" class="sticker-no">{{ $asset->sticker_no }}</a></td>
                    <td><span class="asset-type-badge">{{ $asset->getTypeLabel() }}</span></td>
                    <td>{{ $asset->brand }} {{ $asset->model }}</td>
                    <td><span class="status-badge status-{{ $asset->status }}">{{ $asset->getStatusLabel() }}</span></td>
                    <td>{!! $asset->department ? '<span class="dept-tag">'.$asset->department.'</span>' : '—'  !!}</td>
                    <td>{{ $asset->assigned_to ?? '—' }}</td>
                    <td style="font-family:var(--font-mono);font-size:.72rem;color:var(--text3)">{{ $asset->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:30px;color:var(--text3)">No assets yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection