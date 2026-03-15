@extends('layouts.app')
@section('title', 'Assets — AssetTrack')
@section('breadcrumb', 'All Assets')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Asset Inventory</div>
        <div class="page-subtitle">{{ $assets->total() }} records found</div>
    </div>
    <div class="page-actions">
        <a href="{{ route('assets.create') }}" class="btn btn-primary">
            <svg viewBox="0 0 20 20" fill="none"><path d="M10 4v12M4 10h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Add Asset
        </a>
        <a href="{{ route('reports.index') }}" class="btn btn-outline">
            <svg viewBox="0 0 20 20" fill="none"><path d="M4 3h12a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V4a1 1 0 011-1z" stroke="currentColor" stroke-width="1.5"/></svg>
            Export Report
        </a>
    </div>
</div>

<!-- Summary Counts -->
<div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(120px,1fr));margin-bottom:16px;">
    @foreach(\App\Models\Asset::STATUSES as $key => $label)
    <a href="{{ route('assets.index', array_merge(request()->except('page'), ['status' => $key])) }}" class="stat-card {{ $key }}" style="text-decoration:none;cursor:pointer;">
        <div class="stat-num" style="font-size:1.6rem">{{ $stats[$key] ?? 0 }}</div>
        <div class="stat-label">{{ $label }}</div>
    </a>
    @endforeach
</div>

<!-- Filters -->
<form method="GET" action="{{ route('assets.index') }}" class="filters-bar">
    <div class="filter-group">
        <span class="filter-label">Type</span>
        <select name="type" class="filter-select" onchange="this.form.submit()">
            <option value="">All Types</option>
            @foreach(\App\Models\Asset::TYPES as $k => $v)
            <option value="{{ $k }}" {{ request('type') == $k ? 'selected' : '' }}>{{ $v }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-group">
        <span class="filter-label">Status</span>
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">All Status</option>
            @foreach(\App\Models\Asset::STATUSES as $k => $v)
            <option value="{{ $k }}" {{ request('status') == $k ? 'selected' : '' }}>{{ $v }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-group">
        <span class="filter-label">Dept</span>
        <select name="department" class="filter-select" onchange="this.form.submit()">
            <option value="">All Depts</option>
            @foreach(\App\Models\Asset::DEPARTMENTS as $d)
            <option value="{{ $d }}" {{ request('department') == $d ? 'selected' : '' }}>{{ $d }}</option>
            @endforeach
        </select>
    </div>
    @if(request()->hasAny(['type','status','department','search']))
    <a href="{{ route('assets.index') }}" class="btn btn-sm btn-outline">Clear Filters</a>
    @endif
</form>

<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll" style="accent-color:var(--accent)"></th>
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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assets as $asset)
            <tr>
                <td><input type="checkbox" class="row-check" value="{{ $asset->id }}" style="accent-color:var(--accent)"></td>
                <td><a href="{{ route('assets.show', $asset) }}" class="sticker-no">{{ $asset->sticker_no }}</a></td>
                <td><span class="asset-type-badge">{{ $asset->getTypeLabel() }}</span></td>
                <td>
                    <div style="font-weight:600;color:var(--text)">{{ $asset->brand }}</div>
                    <div style="font-size:.72rem;color:var(--text3)">{{ $asset->model }}</div>
                </td>
                <td style="font-family:var(--font-mono);font-size:.72rem;color:var(--text3)">{{ $asset->serial_no ?? '—' }}</td>
                <td><span class="status-badge status-{{ $asset->status }}">{{ $asset->getStatusLabel() }}</span></td>
                <td>{!! $asset->department ? '<span class="dept-tag">'.$asset->department.'</span>' : '—' !!}</td>
                <td style="font-size:.8rem">{{ $asset->assigned_to ?? '—' }}</td>
                <td style="font-size:.75rem;color:var(--text3)">{{ $asset->old_user ?? '—' }}</td>
                <td style="font-family:var(--font-mono);font-size:.72rem;color:var(--text3)">{{ $asset->date_purchased ? $asset->date_purchased->format('m/d/Y') : '—' }}</td>
<td style="font-family:var(--font-mono);font-size:.72rem;color:var(--text3)">{{ $asset->date_deployed ? $asset->date_deployed->format('m/d/Y') : '—' }}</td>
                <td>
                    <div class="table-actions">
                        <a href="{{ route('assets.show', $asset) }}" class="btn btn-sm btn-outline btn-icon" title="View">
                            <svg viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="3" stroke="currentColor" stroke-width="1.5"/><path d="M2 8c1.5-4 9.5-4 12 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                        </a>
                        <a href="{{ route('assets.edit', $asset) }}" class="btn btn-sm btn-outline btn-icon" title="Edit">
                            <svg viewBox="0 0 16 16" fill="none"><path d="M11 2l3 3-9 9H2v-3L11 2z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
                        </a>
                        <a href="{{ route('assets.barcode', $asset) }}" class="btn btn-sm btn-outline btn-icon" title="Barcode">
                            <svg viewBox="0 0 16 16" fill="none"><path d="M2 4h1v8H2zM4 4h2v8H4zM7 4h1v8H7zM9 4h2v8H9zM12 4h1v8h-1zM14 4h1v8h-1z" fill="currentColor"/></svg>
                        </a>
                        <form method="POST" action="{{ route('assets.destroy', $asset) }}" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger btn-icon confirm-delete" title="Delete">
                                <svg viewBox="0 0 16 16" fill="none"><path d="M3 4h10M5 4V3h6v1M6 7v5M10 7v5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="12">
                    <div class="empty-state">
                        <div class="empty-icon">📦</div>
                        <div class="empty-title">No assets found</div>
                        <div class="empty-desc">Try adjusting your filters or <a href="{{ route('assets.create') }}" style="color:var(--accent)">add a new asset</a>.</div>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div style="display:flex;justify-content:space-between;align-items:center;margin-top:16px;flex-wrap:wrap;gap:12px;">
    <div style="font-size:.75rem;color:var(--text3)">
        Showing {{ $assets->firstItem() }}–{{ $assets->lastItem() }} of {{ $assets->total() }} assets
    </div>
    @if($assets->hasPages())
    <div class="pagination">
        @if($assets->onFirstPage())
        <span class="page-link" style="opacity:.4">←</span>
        @else
        <a href="{{ $assets->previousPageUrl() }}" class="page-link">←</a>
        @endif

        @foreach($assets->getUrlRange(max(1, $assets->currentPage()-2), min($assets->lastPage(), $assets->currentPage()+2)) as $page => $url)
        <a href="{{ $url }}" class="page-link {{ $page == $assets->currentPage() ? 'active' : '' }}">{{ $page }}</a>
        @endforeach

        @if($assets->hasMorePages())
        <a href="{{ $assets->nextPageUrl() }}" class="page-link">→</a>
        @else
        <span class="page-link" style="opacity:.4">→</span>
        @endif
    </div>
    @endif
</div>

<!-- Bulk Barcode Print -->
<div id="bulkActions" style="display:none;margin-top:12px;">
    <form method="POST" action="{{ route('assets.bulk-barcode') }}" id="bulkForm">
        @csrf
        <div id="bulkIdsContainer"></div>
        <button type="submit" class="btn btn-outline">
            <svg viewBox="0 0 16 16" fill="none"><path d="M2 4h1v8H2zM4 4h2v8H4zM7 4h1v8H7zM9 4h2v8H9zM12 4h1v8h-1z" fill="currentColor"/></svg>
            Print Selected Barcodes (<span id="selCount">0</span>)
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
const checkboxes = document.querySelectorAll('.row-check');
const selectAll = document.getElementById('selectAll');
const bulkActions = document.getElementById('bulkActions');
const selCount = document.getElementById('selCount');
const bulkIdsContainer = document.getElementById('bulkIdsContainer');

function updateBulk() {
    const checked = [...checkboxes].filter(c => c.checked);
    selCount.textContent = checked.length;
    bulkActions.style.display = checked.length > 0 ? 'block' : 'none';
    bulkIdsContainer.innerHTML = '';
    checked.forEach(c => {
        const inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = 'ids[]';
        inp.value = c.value;
        bulkIdsContainer.appendChild(inp);
    });
}

selectAll?.addEventListener('change', () => {
    checkboxes.forEach(c => c.checked = selectAll.checked);
    updateBulk();
});
checkboxes.forEach(c => c.addEventListener('change', updateBulk));
</script>
@endpush