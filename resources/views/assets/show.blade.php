@extends('layouts.app')
@section('title', $asset->sticker_no . ' — AssetTrack')
@section('breadcrumb', 'Assets / ' . $asset->sticker_no)

@section('content')
<div class="page-header">
    <div>
        <div class="page-title" style="display:flex;align-items:center;gap:12px;">
            {{ $asset->sticker_no }}
            <span class="status-badge status-{{ $asset->status }}">{{ $asset->getStatusLabel() }}</span>
        </div>
        <div class="page-subtitle">
            {{ $asset->getTypeLabel() }} — {{ $asset->brand }} {{ $asset->model }}
            @if($asset->department)
            &nbsp;|&nbsp; <span style="color:var(--accent)">{{ $asset->department }}</span>
            @endif
        </div>
    </div>
    <div class="page-actions">
        <a href="{{ route('assets.edit', $asset) }}" class="btn btn-primary">
            <svg viewBox="0 0 16 16" fill="none"><path d="M11 2l3 3-9 9H2v-3L11 2z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
            Edit
        </a>
        <a href="{{ route('assets.barcode', $asset) }}" class="btn btn-outline">
            Print QR Sticker
        </a>
        <a href="{{ route('assets.index') }}" class="btn btn-outline">← Back</a>
    </div>
</div>

<!-- Sticker No breakdown -->
<div style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius-lg);padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
    <div style="font-family:var(--font-mono);font-size:1.6rem;font-weight:800;color:var(--accent);letter-spacing:.06em;">
        {{ $asset->sticker_no }}
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <div style="background:var(--bg3);border:1px solid var(--border2);border-radius:6px;padding:6px 12px;text-align:center;">
            <div style="font-family:var(--font-mono);font-size:1rem;font-weight:800;color:var(--working);">{{ $asset->getDeptCode() }}</div>
            <div style="font-size:.62rem;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;">Dept Code</div>
        </div>
        <div style="background:var(--bg3);border:1px solid var(--border2);border-radius:6px;padding:6px 12px;text-align:center;">
            <div style="font-family:var(--font-mono);font-size:1rem;font-weight:800;color:var(--text);">
                @php preg_match('/(\d+)/', $asset->sticker_no, $m); echo $m[1] ?? '0001'; @endphp
            </div>
            <div style="font-size:.62rem;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;">Sequence</div>
        </div>
        <div style="background:var(--bg3);border:1px solid var(--border2);border-radius:6px;padding:6px 12px;text-align:center;">
            <div style="font-family:var(--font-mono);font-size:1rem;font-weight:800;color:var(--accent);">{{ $asset->getTypeCode() }}</div>
            <div style="font-size:.62rem;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;">Type Code</div>
        </div>
        <div style="background:var(--bg3);border:1px solid var(--border2);border-radius:6px;padding:6px 12px;text-align:center;display:flex;flex-direction:column;justify-content:center;">
            <div style="font-size:.72rem;color:var(--text2);">{{ $asset->getDeptCode() }} = {{ $asset->department ?? 'N/A' }}</div>
            <div style="font-size:.72rem;color:var(--text2);">{{ $asset->getTypeCode() }} = {{ $asset->getTypeLabel() }}</div>
        </div>
    </div>
</div>

<div class="detail-grid">
    <!-- Left: Details -->
    <div style="display:flex;flex-direction:column;gap:16px;">

        <!-- Asset Info -->
        <div class="detail-card">
            <div class="detail-card-header">
                <span class="detail-card-title">ASSET DETAILS</span>
                <span class="asset-type-badge">{{ $asset->getTypeLabel() }}</span>
            </div>
            <div class="detail-card-body">
                <div class="detail-rows">
                    <div class="detail-row">
                        <div class="detail-key">Sticker No</div>
                        <div class="detail-val sticker-no">{{ $asset->sticker_no }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-key">Type</div>
                        <div class="detail-val">{{ $asset->getTypeLabel() }} <span style="font-family:var(--font-mono);color:var(--accent);font-size:.75rem;">({{ $asset->getTypeCode() }})</span></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-key">Brand</div>
                        <div class="detail-val">{{ $asset->brand }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-key">Model</div>
                        <div class="detail-val">{{ $asset->model ?? '—' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-key">Serial Number</div>
                        <div class="detail-val" style="font-family:var(--font-mono);font-size:.8rem">{{ $asset->serial_no ?? '—' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-key">Status</div>
                        <div class="detail-val"><span class="status-badge status-{{ $asset->status }}">{{ $asset->getStatusLabel() }}</span></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-key">Supplier</div>
                        <div class="detail-val">{{ $asset->supplier ?? '—' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-key">Purchase Cost</div>
                        <div class="detail-val">{{ $asset->purchase_cost ? '₱ ' . number_format($asset->purchase_cost, 2) : '—' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-key">Date Purchased</div>
                        <div class="detail-val">{{ $asset->date_purchased ? $asset->date_purchased->format('F d, Y') : '—' }}</div>
                    </div>
                    @if($asset->specs)
                    <div class="detail-row">
                        <div class="detail-key">Specifications</div>
                        <div class="detail-val" style="font-size:.78rem;line-height:1.7">{{ $asset->specs }}</div>
                    </div>
                    @endif
                    @if($asset->remarks)
                    <div class="detail-row">
                        <div class="detail-key">Remarks</div>
                        <div class="detail-val" style="font-size:.78rem;color:var(--disposal)">{{ $asset->remarks }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Deployment Info -->
        <div class="detail-card">
            <div class="detail-card-header">
                <span class="detail-card-title">DEPLOYMENT INFORMATION</span>
                @if($asset->isDeployed())
                <span class="status-badge status-working" style="font-size:.65rem">Deployed</span>
                @else
                <span class="status-badge status-disposed" style="font-size:.65rem">Not Deployed</span>
                @endif
            </div>
            <div class="detail-card-body">
                <div class="detail-rows">
                    <div class="detail-row">
                        <div class="detail-key">Department</div>
                        <div class="detail-val">
                            @if($asset->department)
                            <span class="dept-tag">{{ $asset->department }}</span>
                            <!-- <span style="font-family:var(--font-mono);font-size:.72rem;color:var(--accent);margin-left:6px;">({{ $asset->getDeptCode() }})</span> -->
                            @else
                            —
                            @endif
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-key">Assigned To</div>
                        <div class="detail-val" style="font-weight:600">{{ $asset->assigned_to ?? '—' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-key">Old / Prev. User</div>
                        <div class="detail-val" style="color:var(--text2)">{{ $asset->old_user ?? '—' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-key">Date Deployed</div>
                        <div class="detail-val">{{ $asset->date_deployed ? $asset->date_deployed->format('F d, Y') : '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Right: QR Sticker Preview + Danger Zone -->
    <div style="display:flex;flex-direction:column;gap:16px;">

        <!-- QR Sticker Preview -->
        <div class="detail-card">
            <div class="detail-card-header">
                <span class="detail-card-title">QR STICKER PREVIEW</span>
            </div>
            <div class="detail-card-body" style="display:flex;flex-direction:column;align-items:center;gap:16px;">

                <!-- Live Sticker Preview -->
                <div id="sticker-{{ $asset->id }}" style="background:#fff;color:#000;border-radius:8px;padding:12px 14px;display:flex;flex-direction:column;align-items:center;gap:5px;min-width:190px;border:2.5px solid #222;font-family:monospace;">
                    <div style="font-size:7.5px;font-weight:800;letter-spacing:.15em;text-transform:uppercase;">YOUR ORGANIZATION</div>

                    <!-- QR Code -->
                    <div data-qr="{{ $asset->qr_data ?? $asset->sticker_no }}" data-size="110" style="margin:4px 0;"></div>

                    <div style="width:100%;border-top:1.5px solid #333;margin:2px 0;"></div>
                    <div style="font-size:13px;font-weight:800;letter-spacing:.06em;color:#000;">{{ $asset->sticker_no }}</div>
                    <div style="display:flex;align-items:center;gap:5px;font-size:8px;font-weight:700;text-transform:uppercase;">
                        <span style="background:#000;color:#fff;padding:2px 5px;border-radius:3px;font-size:7.5px;font-weight:800;">{{ $asset->getTypeCode() }}</span>
                        {{ $asset->getTypeLabel() }}
                    </div>
                    <div style="width:100%;border-top:1px solid #ccc;margin:2px 0;"></div>
                    <div style="font-size:7.5px;text-align:center;line-height:1.7;color:#222;font-family:sans-serif;width:100%;">
                        @if($asset->department)<div>Dept: {{ $asset->department }}</div>@endif
                        @if($asset->assigned_to)<div>User: {{ $asset->assigned_to }}</div>@endif
                        @if($asset->date_deployed)<div>Deployed: {{ $asset->date_deployed->format('m/d/Y') }}</div>@endif
                        @if($asset->date_purchased)<div>Purchased: {{ $asset->date_purchased->format('m/d/Y') }}</div>@endif
                    </div>
                </div>

                <div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center;">
                    <a href="{{ route('assets.barcode', $asset) }}" class="btn btn-primary">
                        🖨️ Print Full Sticker
                    </a>
                    <button class="btn btn-outline" onclick="printSticker({{ $asset->id }})">Quick Print</button>
                </div>

                <!-- QR Data info -->
                <div style="width:100%;background:var(--bg3);border:1px solid var(--border);border-radius:6px;padding:10px;font-size:.7rem;">
                    <div style="color:var(--text3);font-weight:700;letter-spacing:.08em;text-transform:uppercase;margin-bottom:4px;">QR Data Encoded</div>
                    <div style="font-family:var(--font-mono);font-size:.68rem;color:var(--text2);word-break:break-all;">{{ $asset->qr_data ?? $asset->sticker_no }}</div>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="detail-card" style="border-color:rgba(248,113,113,0.2)">
            <div class="detail-card-header" style="background:rgba(248,113,113,0.05)">
                <span class="detail-card-title" style="color:var(--defective)">DANGER ZONE</span>
            </div>
            <div class="detail-card-body">
                <p style="font-size:.78rem;color:var(--text3);margin-bottom:12px;">Permanently remove this asset record from the system.</p>
                <form method="POST" action="{{ route('assets.destroy', $asset) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger confirm-delete" style="width:100%;justify-content:center;">
                        <svg viewBox="0 0 16 16" fill="none"><path d="M3 4h10M5 4V3h6v1M6 7v5M10 7v5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                        Delete Asset
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection