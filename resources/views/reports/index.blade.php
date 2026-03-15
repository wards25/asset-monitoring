@extends('layouts.app')
@section('title', 'Reports — AssetTrack')
@section('breadcrumb', 'Reports')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Asset Reports</div>
        <div class="page-subtitle">Filter and export asset data for reporting</div>
    </div>
    <button class="btn btn-primary" onclick="window.print()">
        <svg viewBox="0 0 16 16" fill="none"><path d="M4 5V2h8v3M4 11H2V6h12v5h-2M4 9h8v5H4V9z" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/></svg>
        Print / Export
    </button>
</div>

<!-- Filters -->
<form method="GET" action="{{ route('reports.index') }}" class="filters-bar" style="margin-bottom:20px;">
    <div class="filter-group">
        <span class="filter-label">Department</span>
        <select name="department" class="filter-select">
            <option value="">All Departments</option>
            @foreach($departments as $d)
            <option value="{{ $d }}" {{ request('department') == $d ? 'selected' : '' }}>{{ $d }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-group">
        <span class="filter-label">Status</span>
        <select name="status" class="filter-select">
            <option value="">All Status</option>
            @foreach(\App\Models\Asset::STATUSES as $k => $v)
            <option value="{{ $k }}" {{ request('status') == $k ? 'selected' : '' }}>{{ $v }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-group">
        <span class="filter-label">Type</span>
        <select name="type" class="filter-select">
            <option value="">All Types</option>
            @foreach(\App\Models\Asset::TYPES as $k => $v)
            <option value="{{ $k }}" {{ request('type') == $k ? 'selected' : '' }}>{{ $v }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary btn-sm">Apply</button>
    @if(request()->hasAny(['department','status','type']))
    <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline">Clear</a>
    @endif
</form>

<!-- Summary Cards -->
<div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(140px,1fr));margin-bottom:20px;">
    <div class="stat-card total">
        <div class="stat-num" style="font-size:1.8rem">{{ $summary['total'] }}</div>
        <div class="stat-label">Total</div>
    </div>
    <div class="stat-card working">
        <div class="stat-num" style="font-size:1.8rem">{{ $summary['deployed'] }}</div>
        <div class="stat-label">Deployed</div>
    </div>
    @foreach(\App\Models\Asset::STATUSES as $k => $v)
    <div class="stat-card {{ $k }}">
        <div class="stat-num" style="font-size:1.8rem">{{ $summary['by_status'][$k] ?? 0 }}</div>
        <div class="stat-label">{{ $v }}</div>
    </div>
    @endforeach
</div>

<!-- Full Asset Table -->
<div class="table-wrapper">
    <table class="data-table" style="font-size:.75rem;">
        <thead>
            <tr>
                <th>#</th>
                <th>Sticker No</th>
                <th>Type</th>
                <th>Brand / Model</th>
                <th>Serial No</th>
                <th>Status</th>
                <th>Department</th>
                <th>Assigned To</th>
                <th>Old User</th>
                <th>Date Purchased</th>
                <th>Date Deployed</th>
                <th>Cost (₱)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assets as $i => $asset)
            <tr>
                <td style="color:var(--text3)">{{ $i + 1 }}</td>
                <td><a href="{{ route('assets.show', $asset) }}" class="sticker-no" style="font-size:.72rem">{{ $asset->sticker_no }}</a></td>
                <td><span class="asset-type-badge" style="font-size:.62rem">{{ $asset->getTypeLabel() }}</span></td>
                <td>
                    <span style="font-weight:600;">{{ $asset->brand }}</span>
                    <span style="color:var(--text3)"> {{ $asset->model }}</span>
                </td>
                <td style="font-family:var(--font-mono);font-size:.68rem;color:var(--text3)">{{ $asset->serial_no ?? '—' }}</td>
                <td><span class="status-badge status-{{ $asset->status }}" style="font-size:.65rem">{{ $asset->getStatusLabel() }}</span></td>
                <td>{{ $asset->department ?? '—' }}</td>
                <td>{{ $asset->assigned_to ?? '—' }}</td>
                <td style="color:var(--text3)">{{ $asset->old_user ?? '—' }}</td>
                <td style="font-family:var(--font-mono);font-size:.68rem;color:var(--text3)">
                    {{ $asset->date_purchased ? $asset->date_purchased->format('m/d/Y') : '—' }}
                </td>
                <td style="font-family:var(--font-mono);font-size:.68rem;color:var(--text3)">
                    {{ $asset->date_deployed ? $asset->date_deployed->format('m/d/Y') : '—' }}
                </td>
                <td style="font-family:var(--font-mono);font-size:.7rem;text-align:right;">
                    {{ $asset->purchase_cost ? number_format($asset->purchase_cost, 2) : '—' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="12">
                    <div class="empty-state">
                        <div class="empty-icon">📊</div>
                        <div class="empty-title">No assets match your filters</div>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
        @if($assets->isNotEmpty())
        <tfoot>
            <tr style="background:var(--bg3);">
                <td colspan="10" style="padding:10px 14px;font-family:var(--font-mono);font-size:.7rem;color:var(--text3);font-weight:700;">
                    TOTAL: {{ $summary['total'] }} assets
                </td>
                <td colspan="2" style="padding:10px 14px;font-family:var(--font-mono);font-size:.75rem;font-weight:700;text-align:right;color:var(--accent);">
                    ₱ {{ number_format($assets->sum('purchase_cost'), 2) }}
                </td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>

<!-- Breakdown Summary -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px;">
    <div class="chart-card">
        <div class="chart-card-header"><div class="chart-card-title">BREAKDOWN BY TYPE</div></div>
        <div class="chart-card-body">
            @foreach(\App\Models\Asset::TYPES as $k => $v)
            @php $cnt = $summary['by_type'][$k] ?? 0; @endphp
            <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border);font-size:.8rem;">
                <span style="color:var(--text2)">{{ $v }}</span>
                <span style="font-family:var(--font-mono);font-weight:700;color:{{ $cnt > 0 ? 'var(--accent)' : 'var(--text3)' }}">{{ $cnt }}</span>
            </div>
            @endforeach
        </div>
    </div>
    <div class="chart-card">
        <div class="chart-card-header"><div class="chart-card-title">BREAKDOWN BY DEPARTMENT</div></div>
        <div class="chart-card-body">
            @forelse($summary['by_dept'] as $dept => $cnt)
            <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border);font-size:.8rem;">
                <span style="color:var(--text2)">{{ $dept ?: '(Unassigned)' }}</span>
                <span style="font-family:var(--font-mono);font-weight:700;color:var(--working)">{{ $cnt }}</span>
            </div>
            @empty
            <div style="color:var(--text3);font-size:.8rem;padding:10px 0">No department data.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection