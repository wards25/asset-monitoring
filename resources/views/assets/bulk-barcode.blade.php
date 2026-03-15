<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk QR Stickers — AssetTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; background: #0f0f0f; color: #e8e4dc; padding: 24px; }
        .no-print { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .no-print h1 { font-family: 'Space Mono', monospace; font-size: 1.1rem; }
        .btn { display: inline-flex; align-items: center; gap: 7px; padding: 8px 16px; border-radius: 8px; font-size: .82rem; font-weight: 600; cursor: pointer; text-decoration: none; border: 1px solid transparent; }
        .btn-primary { background: #e8a838; color: #000; }
        .btn-outline { background: transparent; color: #e8e4dc; border-color: #333; }

        .sticker-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
            gap: 16px;
        }

        .sticker {
            background: #fff; color: #000;
            border: 2px solid #111; border-radius: 8px;
            padding: 10px 12px;
            display: flex; flex-direction: column;
            align-items: center; gap: 4px;
            font-family: 'Space Mono', monospace;
            page-break-inside: avoid;
        }
        .sticker-org { font-size: 7px; font-weight: 700; letter-spacing: .15em; text-transform: uppercase; }
        .sticker-qr { display: flex; align-items: center; justify-content: center; margin: 3px 0; }
        .sticker-qr img, .sticker-qr canvas { width: 90px !important; height: 90px !important; }
        hr { width: 100%; border: none; border-top: 1px solid #333; margin: 3px 0; }
        .sticker-no { font-size: 12px; font-weight: 800; letter-spacing: .06em; }
        .type-badge { background: #000; color: #fff; padding: 1px 5px; border-radius: 3px; font-size: 7px; font-weight: 800; }
        .type-row { display: flex; align-items: center; gap: 5px; font-size: 7.5px; font-weight: 700; text-transform: uppercase; }
        .sticker-info { font-size: 7px; text-align: center; line-height: 1.6; color: #222; font-family: 'DM Sans', sans-serif; width: 100%; }
        .sticker-info .row { display: flex; justify-content: space-between; gap: 6px; border-bottom: 0.5px solid #eee; padding: 1px 0; }
        .sticker-info .row:last-child { border-bottom: none; }
        .sticker-info .key { color: #666; font-weight: 600; }

        @media print {
            body { background: #fff; padding: 8px; }
            .no-print { display: none !important; }
            .sticker { break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <div>
            <h1>BULK QR STICKERS — {{ count($assets) }} assets</h1>
            <div style="font-size:.72rem;color:#9a9490;margin-top:3px;font-family:monospace;">
                Format: {DEPT}-{0001}-{TYPE} &nbsp;|&nbsp; e.g. SD-0001-M = Sales, Mouse #1
            </div>
        </div>
        <div style="display:flex;gap:10px;">
            <a href="{{ route('assets.index') }}" class="btn btn-outline">← Back</a>
            <button onclick="window.print()" class="btn btn-primary">🖨️ Print All</button>
        </div>
    </div>

    <div class="sticker-grid">
        @foreach($assets as $asset)
        <div class="sticker">
            <div class="sticker-org">IT Asset Management</div>
            <div class="sticker-qr" data-qr="{{ $asset->qr_data ?? $asset->sticker_no }}" data-size="90"></div>
            <hr>
            <div class="sticker-no">{{ $asset->sticker_no }}</div>
            <div class="type-row">
                <span class="type-badge">{{ $asset->getTypeCode() }}</span>
                {{ $asset->getTypeLabel() }}
            </div>
            <hr>
            <div class="sticker-info">
                <div class="row"><span class="key">Brand</span><span>{{ $asset->brand }}</span></div>
                @if($asset->department)
                <div class="row"><span class="key">Dept</span><span>{{ $asset->department }}</span></div>
                @endif
                @if($asset->assigned_to)
                <div class="row"><span class="key">User</span><span>{{ $asset->assigned_to }}</span></div>
                @endif
                @if($asset->date_deployed)
                <div class="row"><span class="key">Deployed</span><span>{{ $asset->date_deployed->format('m/d/Y') }}</span></div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <script>
    window.addEventListener('load', function() {
        document.querySelectorAll('[data-qr]').forEach(function(el) {
            var text = el.getAttribute('data-qr');
            var size = parseInt(el.getAttribute('data-size') || '90');
            el.innerHTML = '';
            new QRCode(el, {
                text: text, width: size, height: size,
                colorDark: '#000000', colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.M
            });
        });
    });
    </script>
</body>
</html>