@extends('layouts.app')
@section('title', 'Scan Barcode — AssetTrack')
@section('breadcrumb', 'Assets / Scan Barcode')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Scan Barcode</div>
        <div class="page-subtitle">Use camera or type barcode / sticker number manually</div>
    </div>
    <a href="{{ route('assets.index') }}" class="btn btn-outline">← Back</a>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;max-width:860px;">

    <!-- Camera Scanner -->
    <div class="scanner-container">
        <div class="form-card-header" style="padding:14px 18px;border-bottom:1px solid var(--border);background:var(--bg3);">
            <div class="form-card-title" style="font-family:var(--font-mono);font-size:.82rem;font-weight:700;color:var(--text);">CAMERA SCANNER</div>
        </div>
        <div class="scanner-video-wrap" id="scannerVideoWrap">
            <video id="scannerVideo" autoplay playsinline muted></video>
            <div class="scanner-overlay">
                <div style="position:relative;display:flex;align-items:center;justify-content:center;">
                    <div class="scanner-frame"></div>
                    <div class="scanner-line" id="scannerLine"></div>
                </div>
            </div>
            <div id="scannerStatus" style="position:absolute;bottom:10px;left:50%;transform:translateX(-50%);background:rgba(0,0,0,.7);color:#e8a838;font-family:monospace;font-size:.72rem;padding:4px 12px;border-radius:20px;white-space:nowrap;">
                Camera not started
            </div>
        </div>
        <div class="scanner-body">
            <div style="display:flex;gap:8px;justify-content:center;margin-bottom:12px;">
                <button id="startScanBtn" class="btn btn-primary" onclick="startCamera()">
                    <svg viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="3" fill="currentColor"/><path d="M1 6V4a1 1 0 011-1h2.5L6 2h4l1.5 1H14a1 1 0 011 1v8a1 1 0 01-1 1H2a1 1 0 01-1-1V8" stroke="currentColor" stroke-width="1.2"/></svg>
                    Start Camera
                </button>
                <button id="stopScanBtn" class="btn btn-outline" onclick="stopCamera()" style="display:none;">
                    Stop Camera
                </button>
            </div>
            <p style="font-size:.72rem;color:var(--text3);text-align:center;">Point camera at barcode. Allow camera access when prompted.</p>
        </div>
    </div>

    <!-- Manual Lookup -->
    <div class="form-card" style="border-radius:var(--radius-lg);">
        <div class="form-card-header">
            <div class="form-card-title">MANUAL LOOKUP</div>
        </div>
        <div class="form-card-body" style="display:flex;flex-direction:column;gap:16px;">
            <div class="form-group">
                <label class="form-label">Barcode or Sticker Number</label>
                <div style="display:flex;gap:8px;">
                    <input type="text" id="manualCode" class="form-input" placeholder="e.g. SU-0001 or SU00012024" autofocus>
                    <button class="btn btn-primary" onclick="lookupCode(document.getElementById('manualCode').value)">
                        Search
                    </button>
                </div>
            </div>

            <!-- Result -->
            <div id="scanResult" style="display:none;">
                <div id="scanResultFound" style="display:none;">
                    <div style="background:var(--bg3);border:1px solid var(--border2);border-radius:var(--radius);padding:14px;margin-bottom:10px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                            <span id="resSticker" style="font-family:var(--font-mono);font-size:1rem;font-weight:700;color:var(--accent)"></span>
                            <span id="resStatus" class="status-badge"></span>
                        </div>
                        <div style="font-size:.8rem;display:flex;flex-direction:column;gap:6px;">
                            <div style="display:flex;gap:8px;">
                                <span style="color:var(--text3);min-width:90px;font-size:.72rem;text-transform:uppercase;font-weight:600;">Type</span>
                                <span id="resType"></span>
                            </div>
                            <div style="display:flex;gap:8px;">
                                <span style="color:var(--text3);min-width:90px;font-size:.72rem;text-transform:uppercase;font-weight:600;">Brand</span>
                                <span id="resBrand"></span>
                            </div>
                            <div style="display:flex;gap:8px;">
                                <span style="color:var(--text3);min-width:90px;font-size:.72rem;text-transform:uppercase;font-weight:600;">Department</span>
                                <span id="resDept"></span>
                            </div>
                            <div style="display:flex;gap:8px;">
                                <span style="color:var(--text3);min-width:90px;font-size:.72rem;text-transform:uppercase;font-weight:600;">Assigned To</span>
                                <span id="resUser"></span>
                            </div>
                            <div style="display:flex;gap:8px;">
                                <span style="color:var(--text3);min-width:90px;font-size:.72rem;text-transform:uppercase;font-weight:600;">Date Deployed</span>
                                <span id="resDeployed"></span>
                            </div>
                        </div>
                    </div>
                    <a id="resLink" href="#" class="btn btn-primary" style="width:100%;justify-content:center;">
                        View Full Details →
                    </a>
                </div>
                <div id="scanResultNotFound" style="display:none;">
                    <div style="background:var(--defective-dim);border:1px solid rgba(248,113,113,.2);border-radius:var(--radius);padding:14px;text-align:center;">
                        <div style="font-size:1.5rem;margin-bottom:6px;">🔍</div>
                        <div style="font-family:var(--font-mono);font-size:.82rem;color:var(--defective);">Asset Not Found</div>
                        <div style="font-size:.75rem;color:var(--text3);margin-top:4px;">No matching barcode or sticker number.</div>
                    </div>
                </div>
            </div>

            <!-- Scan History -->
            <div id="scanHistory" style="display:none;">
                <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text3);margin-bottom:8px;">Recent Scans</div>
                <div id="historyList" style="display:flex;flex-direction:column;gap:4px;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let videoStream = null;
let scanInterval = null;
const history = [];

async function startCamera() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
        videoStream = stream;
        const video = document.getElementById('scannerVideo');
        video.srcObject = stream;
        document.getElementById('startScanBtn').style.display = 'none';
        document.getElementById('stopScanBtn').style.display = '';
        document.getElementById('scannerStatus').textContent = 'Scanning…';

        if ('BarcodeDetector' in window) {
            const detector = new BarcodeDetector({ formats: ['code_128', 'code_39', 'ean_13', 'qr_code'] });
            scanInterval = setInterval(async () => {
                try {
                    const barcodes = await detector.detect(video);
                    if (barcodes.length > 0) {
                        const code = barcodes[0].rawValue;
                        document.getElementById('scannerStatus').textContent = '✓ Detected: ' + code;
                        await lookupCode(code);
                    }
                } catch (e) {}
            }, 500);
        } else {
            document.getElementById('scannerStatus').textContent = 'Camera active – use manual lookup';
        }
    } catch (err) {
        document.getElementById('scannerStatus').textContent = 'Camera access denied';
        alert('Could not access camera: ' + err.message);
    }
}

function stopCamera() {
    if (videoStream) { videoStream.getTracks().forEach(t => t.stop()); videoStream = null; }
    if (scanInterval) { clearInterval(scanInterval); scanInterval = null; }
    document.getElementById('scannerVideo').srcObject = null;
    document.getElementById('startScanBtn').style.display = '';
    document.getElementById('stopScanBtn').style.display = 'none';
    document.getElementById('scannerStatus').textContent = 'Camera stopped';
}

document.getElementById('manualCode').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') lookupCode(e.target.value);
});

async function lookupCode(code) {
    if (!code || !code.trim()) return;
    code = code.trim().toUpperCase();
    try {
        const resp = await fetch('{{ route("assets.lookup") }}?code=' + encodeURIComponent(code));
        const data = await resp.json();
        showResult(data, code);
        addHistory(code, data.found, data.asset);
    } catch (e) {
        showResult({ found: false }, code);
    }
}

function showResult(data, code) {
    const resultDiv = document.getElementById('scanResult');
    const found = document.getElementById('scanResultFound');
    const notFound = document.getElementById('scanResultNotFound');
    resultDiv.style.display = 'block';

    if (data.found && data.asset) {
        found.style.display = 'block';
        notFound.style.display = 'none';
        const a = data.asset;
        document.getElementById('resSticker').textContent = a.sticker_no;
        const statusEl = document.getElementById('resStatus');
        statusEl.textContent = a.status_label;
        statusEl.className = 'status-badge status-' + a.status;
        document.getElementById('resType').textContent = a.type;
        document.getElementById('resBrand').textContent = (a.brand || '') + ' ' + (a.model || '');
        document.getElementById('resDept').textContent = a.department || '—';
        document.getElementById('resUser').textContent = a.assigned_to || '—';
        document.getElementById('resDeployed').textContent = a.date_deployed || '—';
        document.getElementById('resLink').href = a.url;
    } else {
        found.style.display = 'none';
        notFound.style.display = 'block';
    }
}

function addHistory(code, found, asset) {
    history.unshift({ code, found, asset, time: new Date().toLocaleTimeString() });
    if (history.length > 5) history.pop();
    renderHistory();
}

function renderHistory() {
    const list = document.getElementById('historyList');
    const container = document.getElementById('scanHistory');
    if (history.length === 0) { container.style.display = 'none'; return; }
    container.style.display = 'block';
    list.innerHTML = history.map(function(h) {
        return '<div style="display:flex;align-items:center;gap:8px;padding:6px 10px;background:var(--bg3);border-radius:6px;font-size:.75rem;">'
            + '<span style="color:' + (h.found ? 'var(--working)' : 'var(--defective)') + ';">' + (h.found ? '✓' : '✗') + '</span>'
            + '<span style="font-family:monospace;color:var(--accent)">' + h.code + '</span>'
            + '<span style="color:var(--text3);flex:1">' + (h.found ? ((h.asset.brand || '') + ' ' + (h.asset.type || '')) : 'Not found') + '</span>'
            + '<span style="color:var(--text3)">' + h.time + '</span>'
            + (h.found ? '<a href="' + h.asset.url + '" style="color:var(--accent);font-size:.7rem;">View</a>' : '')
            + '</div>';
    }).join('');
}
</script>
@endpush