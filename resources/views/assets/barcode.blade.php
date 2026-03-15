<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sticker — {{ $asset->sticker_no }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #0f0f0f;
            color: #e8e4dc;
            min-height: 100vh;
            padding: 24px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .top-bar h1 {
            font-family: 'Space Mono', monospace;
            font-size: 1.1rem;
            color: #e8e4dc;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: .82rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: 1px solid transparent;
        }

        .btn-primary {
            background: #e8a838;
            color: #000;
        }

        .btn-outline {
            background: transparent;
            color: #e8e4dc;
            border-color: #333;
        }

        .sticker-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
            gap: 28px;
            align-items: start;
        }

        .sticker-label {
            font-size: .68rem;
            color: #9a9490;
            margin-bottom: 8px;
            font-family: 'Space Mono', monospace;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        /* ── Base sticker ── */
        .sticker {
            background: #fff;
            color: #000;
            border: 2.5px solid #111;
            border-radius: 8px;
            padding: 12px 14px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            font-family: 'Space Mono', monospace;
            width: fit-content;
            min-width: 190px;
        }

        .sticker-org {
            font-size: 7.5px;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: #000;
            text-align: center;
        }

        .sticker-qr {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 4px 0;
        }

        .sticker-qr img,
        .sticker-qr canvas {
            width: 110px !important;
            height: 110px !important;
        }

        .sticker-divider {
            width: 100%;
            border: none;
            border-top: 1.5px solid #333;
            margin: 3px 0;
        }

        .sticker-no-big {
            font-size: 14px;
            font-weight: 800;
            letter-spacing: .08em;
            color: #000;
            text-align: center;
        }

        .sticker-type-row {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: #000;
        }

        .sticker-type-badge {
            background: #000;
            color: #fff;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7.5px;
            font-weight: 800;
            letter-spacing: .08em;
        }

        .sticker-details {
            font-size: 7.5px;
            line-height: 1.7;
            color: #222;
            font-family: 'DM Sans', sans-serif;
            width: 100%;
        }

        .sticker-details .row {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            padding: 1px 0;
            border-bottom: 0.5px solid #eee;
        }

        .sticker-details .row:last-child {
            border-bottom: none;
        }

        .sticker-details .key {
            color: #666;
            font-weight: 600;
            white-space: nowrap;
        }

        .sticker-details .val {
            color: #000;
            text-align: right;
        }

        .sticker-status {
            font-size: 7px;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: 2px 10px;
            border-radius: 20px;
            margin-top: 2px;
        }

        .status-working {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-new {
            background: #dcfce7;
            color: #166534;
        }

        .status-defective {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-for_disposal {
            background: #ffedd5;
            color: #9a3412;
        }

        .status-disposed {
            background: #f3f4f6;
            color: #374151;
        }

        /* Compact sticker */
        .sticker-compact .sticker-qr img,
        .sticker-compact .sticker-qr canvas {
            width: 80px !important;
            height: 80px !important;
        }

        .sticker-compact .sticker-no-big {
            font-size: 11px;
        }

        .sticker-compact {
            min-width: 155px;
            padding: 8px 10px;
        }

        /* ── QR-Only sticker ── */
        .sticker-qr-only {
            background: #fff;
            color: #000;
            border: 2.5px solid #111;
            border-radius: 8px;
            padding: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            font-family: 'Space Mono', monospace;
            width: fit-content;
            min-width: 140px;
        }

        .sticker-qr-only .qr-wrap img,
        .sticker-qr-only .qr-wrap canvas {
            width: 130px !important;
            height: 130px !important;
        }

        .sticker-qr-only .qr-sticker-no {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .1em;
            color: #000;
            text-align: center;
            margin-top: 2px;
        }

        .sticker-qr-only .qr-sub {
            font-size: 7px;
            font-weight: 600;
            letter-spacing: .06em;
            color: #444;
            text-align: center;
            font-family: 'DM Sans', sans-serif;
        }

        @media print {
            body {
                background: #fff;
                padding: 10px;
            }

            .no-print {
                display: none !important;
            }

            .sticker,
            .sticker-qr-only {
                break-inside: avoid;
            }

            .sticker-grid {
                gap: 16px;
            }
        }
    </style>
</head>

<body>

    <div class="top-bar no-print">
        <div>
            <h1>QR STICKER — {{ $asset->sticker_no }}</h1>
            <div style="font-size:.75rem;color:#9a9490;margin-top:4px;font-family:monospace;">
                {{ $asset->getDeptCode() }}-????-{{ $asset->getTypeCode() }} Format
                &nbsp;|&nbsp;
                {{ $asset->department ?? 'No Dept' }}
                &nbsp;|&nbsp;
                {{ $asset->getTypeLabel() }}
            </div>
        </div>
        <div style="display:flex;gap:10px;">
            <a href="{{ route('assets.show', $asset) }}" class="btn btn-outline">← Back</a>
            <button onclick="window.print()" class="btn btn-primary">🖨️ Print Sticker</button>
        </div>
    </div>

    <!-- Naming Convention Guide -->
    <div class="no-print" style="background:#1a1a1a;border:1px solid #2a2a2a;border-radius:8px;padding:14px 18px;margin-bottom:24px;font-size:.78rem;">
        <div style="font-family:monospace;font-weight:700;color:#e8a838;margin-bottom:8px;letter-spacing:.06em;">NAMING CONVENTION: {DEPT}-{0001}-{TYPE}</div>
        <div style="display:flex;gap:24px;flex-wrap:wrap;color:#9a9490;">
            <div>
                <span style="color:#e8e4dc;font-family:monospace;">C</span> = System Unit &nbsp;
                <span style="color:#e8e4dc;font-family:monospace;">D</span> = Monitor &nbsp;
                <span style="color:#e8e4dc;font-family:monospace;">M</span> = Mouse &nbsp;
                <span style="color:#e8e4dc;font-family:monospace;">K</span> = Keyboard &nbsp;
                <span style="color:#e8e4dc;font-family:monospace;">A</span> = AVR &nbsp;
                <span style="color:#e8e4dc;font-family:monospace;">L</span> = Laptop &nbsp;
                <span style="color:#e8e4dc;font-family:monospace;">P</span> = Printer
            </div>
            <div style="color:#e8a838;font-family:monospace;font-weight:700;">
                Example: SD-0001-M = Sales Dept, Mouse #1
            </div>
        </div>
    </div>

    <div class="sticker-grid">

        <!-- ① Standard Full Sticker -->
        <div>
            <div class="sticker-label">① Standard Label (60mm × 70mm)</div>
            <div class="sticker" id="sticker-{{ $asset->id }}">
                <div class="sticker-org">YOUR ORGANIZATION NAME</div>
                <div class="sticker-qr" data-qr="{{ $asset->qr_data ?? $asset->sticker_no }}" data-size="110"></div>
                <hr class="sticker-divider">
                <div class="sticker-no-big">{{ $asset->sticker_no }}</div>
                <!-- <div class="sticker-type-row">
                    <span class="sticker-type-badge">{{ $asset->getTypeCode() }}</span>
                    {{ $asset->getTypeLabel() }}
                </div> -->
                <hr class="sticker-divider">
                <div class="sticker-details">
                    <div class="row"><span class="key">Brand</span><span class="val">{{ $asset->brand }} {{ $asset->model }}</span></div>
                    @if($asset->department)
                    <div class="row"><span class="key">Dept</span><span class="val">{{ $asset->department }}</span></div>
                    @endif
                    @if($asset->assigned_to)
                    <div class="row"><span class="key">User</span><span class="val">{{ $asset->assigned_to }}</span></div>
                    @endif
                    @if($asset->old_user)
                    <div class="row"><span class="key">Old User</span><span class="val">{{ $asset->old_user }}</span></div>
                    @endif
                    @if($asset->date_purchased)
                    <div class="row"><span class="key">Purchased</span><span class="val">{{ $asset->date_purchased->format('m/d/Y') }}</span></div>
                    @endif
                    @if($asset->date_deployed)
                    <div class="row"><span class="key">Deployed</span><span class="val">{{ $asset->date_deployed->format('m/d/Y') }}</span></div>
                    @endif
                    @if($asset->serial_no)
                    <div class="row"><span class="key">S/N</span><span class="val">{{ $asset->serial_no }}</span></div>
                    @endif
                </div>
                <span class="sticker-status status-{{ $asset->status }}">{{ $asset->getStatusLabel() }}</span>
            </div>
        </div>

        <!-- ② Compact Sticker -->
        <div>
            <div class="sticker-label">② Compact Label (40mm × 50mm)</div>
            <div class="sticker sticker-compact">
                <div class="sticker-org" style="font-size:6.5px;">YOUR ORG NAME</div>
                <div class="sticker-qr" data-qr="{{ $asset->qr_data ?? $asset->sticker_no }}" data-size="80"></div>
                <hr class="sticker-divider">
                <div class="sticker-no-big">{{ $asset->sticker_no }}</div>
                <!-- <div class="sticker-type-row">
                    <span class="sticker-type-badge">{{ $asset->getTypeCode() }}</span>
                    {{ $asset->getTypeLabel() }}
                </div> -->
                <div class="sticker-details" style="font-size:7px;">
                    @if($asset->department)
                    <div class="row"><span class="key">Dept</span><span class="val">{{ $asset->department }}</span></div>
                    @endif
                    @if($asset->assigned_to)
                    <div class="row"><span class="key">User</span><span class="val">{{ $asset->assigned_to }}</span></div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ③ QR Code Only -->
        <div>
            <div class="sticker-label">③ Compact Label (25mm × 25mm)</div>
            <div class="sticker-qr-only">
                <!-- Big QR, nothing else -->
                <div class="qr-wrap" data-qr="{{ $asset->qr_data ?? $asset->sticker_no }}" data-size="130"></div>
                <!-- <div class="qr-sticker-no">{{ $asset->sticker_no }}</div> -->
                <!-- <div class="qr-sub">
                {{ $asset->getTypeCode() }} — {{ $asset->getDeptCode() }}
            </div> -->
            </div>
        </div>

    </div>

    <script>
        window.addEventListener('load', function() {
            document.querySelectorAll('[data-qr]').forEach(function(el) {
                var text = el.getAttribute('data-qr');
                var size = parseInt(el.getAttribute('data-size') || '110');
                el.innerHTML = '';
                new QRCode(el, {
                    text: text,
                    width: size,
                    height: size,
                    colorDark: '#000000',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.M
                });
            });
        });
    </script>
</body>

</html>