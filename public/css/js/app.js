require('./bootstrap');
// AssetTrack — Main JS

document.addEventListener('DOMContentLoaded', () => {

    // ── Mobile Sidebar Toggle ──
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.querySelector('.sidebar');
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });
        document.addEventListener('click', (e) => {
            if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    }

    // ── Auto-dismiss alerts ──
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity .5s';
            setTimeout(() => alert.remove(), 500);
        }, 4000);
    });

    // ── Confirm delete ──
    document.querySelectorAll('.confirm-delete').forEach(btn => {
        btn.addEventListener('click', (e) => {
            if (!confirm('Are you sure you want to delete this asset? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });

    // ── Status color preview on select change ──
    const statusSelect = document.getElementById('status');
    if (statusSelect) {
        statusSelect.addEventListener('change', () => {
            statusSelect.className = 'form-select status-select-' + statusSelect.value;
        });
    }

    // ── Print barcode ──
    window.printBarcode = (id) => {
        const frame = document.getElementById('barcode-' + id);
        if (!frame) return;
        const w = window.open('', '_blank', 'width=400,height=300');
        w.document.write('<html><head><title>Barcode</title><style>body{margin:0;display:flex;justify-content:center;align-items:center;min-height:100vh;background:#fff;}</style></head><body>');
        w.document.write(frame.innerHTML);
        w.document.write('</body></html>');
        w.document.close();
        w.focus();
        setTimeout(() => { w.print(); w.close(); }, 500);
    };

    // ── Generate Code128 Barcode (pure JS, no lib needed) ──
    window.generateBarcode = (text, canvasId) => {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;
        drawCode128(canvas, text);
    };

    // ── Animate stat bars on load ──
    document.querySelectorAll('.type-bar-fill').forEach(bar => {
        const w = bar.getAttribute('data-width') || '0';
        bar.style.width = '0';
        setTimeout(() => { bar.style.width = w + '%'; }, 100);
    });

    // ── Barcode Scanner via QuaggaJS ──
    const scannerSection = document.getElementById('scannerSection');
    if (scannerSection && typeof Quagga !== 'undefined') {
        initBarcodeScanner();
    }
});

// ── Simple Code128 Barcode Renderer ──
function drawCode128(canvas, text) {
    // Encode as Code128 B
    const START_B = 104;
    const STOP = 106;
    const CODE128_B = [
        [2,1,2,2,2,2],[2,2,2,1,2,2],[2,2,2,2,2,1],[1,2,1,2,2,3],[1,2,1,3,2,2],
        [1,3,1,2,2,2],[1,2,2,2,1,3],[1,2,2,3,1,2],[1,3,2,2,1,2],[2,2,1,2,1,3],
        [2,2,1,3,1,2],[2,3,1,2,1,2],[1,1,2,2,3,2],[1,2,2,1,3,2],[1,2,2,2,3,1],
        [1,1,3,2,2,2],[1,2,3,1,2,2],[1,2,3,2,2,1],[2,2,3,2,1,1],[2,2,1,1,3,2],
        [2,2,1,2,3,1],[2,1,3,2,1,2],[2,2,3,1,1,2],[3,1,2,1,3,1],[3,1,1,2,2,2],
        [3,2,1,1,2,2],[3,2,1,2,2,1],[3,1,2,2,1,2],[3,2,2,1,1,2],[3,2,2,2,1,1],
        [2,1,2,1,2,3],[2,1,2,3,2,1],[2,3,2,1,2,1],[1,1,1,3,2,3],[1,3,1,1,2,3],
        [1,3,1,3,2,1],[1,1,2,3,1,3],[1,3,2,1,1,3],[1,3,2,3,1,1],[2,1,1,3,1,3],
        [2,3,1,1,1,3],[2,3,1,3,1,1],[1,1,2,1,3,3],[1,1,2,3,3,1],[1,3,2,1,3,1],
        [1,1,3,1,2,3],[1,1,3,3,2,1],[1,3,3,1,2,1],[3,1,3,1,2,1],[2,1,1,3,3,1],
        [2,3,1,1,3,1],[2,1,3,1,1,3],[2,1,3,3,1,1],[2,1,3,1,3,1],[3,1,1,1,2,3],
        [3,1,1,3,2,1],[3,3,1,1,2,1],[3,1,2,1,1,3],[3,1,2,3,1,1],[3,3,2,1,1,1],
        [3,1,4,1,1,1],[2,2,1,4,1,1],[4,3,1,1,1,1],[1,1,1,2,2,4],[1,1,1,4,2,2],
        [1,2,1,1,2,4],[1,2,1,4,2,1],[1,4,1,1,2,2],[1,4,1,2,2,1],[1,1,2,2,1,4],
        [1,1,2,4,1,2],[1,2,2,1,1,4],[1,2,2,4,1,1],[1,4,2,1,1,2],[1,4,2,2,1,1],
        [2,4,1,2,1,1],[2,2,1,1,1,4],[4,1,3,1,1,1],[2,4,1,1,1,2],[1,3,4,1,1,1],
        [1,1,1,2,4,2],[1,2,1,1,4,2],[1,2,1,2,4,1],[1,1,4,2,1,2],[1,2,4,1,1,2],
        [1,2,4,2,1,1],[4,1,1,2,1,2],[4,2,1,1,1,2],[4,2,1,2,1,1],[2,1,2,1,4,1],
        [2,1,4,1,2,1],[4,1,2,1,2,1],[1,1,1,1,4,3],[1,1,1,3,4,1],[1,3,1,1,4,1],
        [1,1,4,1,1,3],[1,1,4,3,1,1],[4,1,1,1,1,3],[4,1,1,3,1,1],[1,1,3,1,4,1],
        [4,1,1,1,3,1],[2,1,1,4,1,2],[2,1,1,2,1,4],[2,1,1,2,3,2],[2,3,3,1,1,1],
        [1,1,2,3,2,1] // STOP
    ];

    const chars = [];
    chars.push(START_B);
    let checksum = START_B;
    for (let i = 0; i < text.length; i++) {
        const code = text.charCodeAt(i) - 32;
        chars.push(code);
        checksum += code * (i + 1);
    }
    chars.push(checksum % 103);
    chars.push(STOP);

    // Build bar pattern
    let bars = [];
    for (let c of chars) {
        if (c < CODE128_B.length) {
            bars = bars.concat(CODE128_B[c]);
        }
    }
    // Add termination bar
    bars.push(2);

    const W = canvas.width || 300;
    const H = canvas.height || 80;
    const moduleWidth = W / bars.reduce((a, b) => a + b, 0);

    const ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, W, H);
    ctx.fillStyle = '#fff';
    ctx.fillRect(0, 0, W, H);

    let x = 0;
    let isBar = true;
    for (let i = 0; i < bars.length; i++) {
        const barW = bars[i] * moduleWidth;
        if (isBar) {
            ctx.fillStyle = '#000';
            ctx.fillRect(Math.round(x), 0, Math.max(1, Math.round(barW)), H);
        }
        x += barW;
        isBar = !isBar;
    }
}

// Render all barcodes on page
window.addEventListener('load', () => {
    document.querySelectorAll('[data-barcode]').forEach(canvas => {
        drawCode128(canvas, canvas.getAttribute('data-barcode'));
    });
});