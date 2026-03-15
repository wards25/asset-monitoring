// AssetTrack — Main JS

document.addEventListener('DOMContentLoaded', function() {

    // ── Mobile Sidebar Toggle ──
    var menuToggle = document.getElementById('menuToggle');
    var sidebar = document.querySelector('.sidebar');
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });
        document.addEventListener('click', function(e) {
            if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    }

    // ── Auto-dismiss alerts ──
    document.querySelectorAll('.alert').forEach(function(alert) {
        setTimeout(function() {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity .5s';
            setTimeout(function() { alert.remove(); }, 500);
        }, 4000);
    });

    // ── Confirm delete ──
    document.querySelectorAll('.confirm-delete').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this asset? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });

    // ── Animate stat bars on load ──
    document.querySelectorAll('.type-bar-fill').forEach(function(bar) {
        var w = bar.getAttribute('data-width') || '0';
        bar.style.width = '0';
        setTimeout(function() { bar.style.width = w + '%'; }, 100);
    });

    // ── Generate all QR codes on page ──
    renderAllQRCodes();
});

// ── QR Code Renderer using qrcodejs (loaded from CDN) ──
function renderAllQRCodes() {
    document.querySelectorAll('[data-qr]').forEach(function(el) {
        var text = el.getAttribute('data-qr');
        if (!text) return;
        el.innerHTML = '';
        if (typeof QRCode !== 'undefined') {
            new QRCode(el, {
                text: text,
                width:  el.getAttribute('data-size') ? parseInt(el.getAttribute('data-size')) : 120,
                height: el.getAttribute('data-size') ? parseInt(el.getAttribute('data-size')) : 120,
                colorDark:  '#000000',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.M
            });
        } else {
            // Fallback: show text if QRCode lib not loaded
            el.innerHTML = '<div style="font-family:monospace;font-size:9px;word-break:break-all;padding:4px;">' + text + '</div>';
        }
    });
}

// ── Print sticker ──
window.printSticker = function(id) {
    var frame = document.getElementById('sticker-' + id);
    if (!frame) return;
    var w = window.open('', '_blank', 'width=450,height=400');
    w.document.write('<!DOCTYPE html><html><head><title>Sticker</title>');
    w.document.write('<style>');
    w.document.write('body{margin:0;background:#fff;display:flex;justify-content:center;align-items:center;min-height:100vh;}');
    w.document.write('.sticker{background:#fff;color:#000;border:2.5px solid #000;border-radius:8px;padding:14px 16px;display:flex;flex-direction:column;align-items:center;gap:6px;font-family:monospace;min-width:200px;}');
    w.document.write('</style></head><body>');
    w.document.write(frame.outerHTML);
    w.document.write('<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"><\/script>');
    w.document.write('<script>');
    w.document.write('window.onload=function(){');
    w.document.write('document.querySelectorAll("[data-qr]").forEach(function(el){');
    w.document.write('var t=el.getAttribute("data-qr");');
    w.document.write('el.innerHTML="";');
    w.document.write('new QRCode(el,{text:t,width:120,height:120,colorDark:"#000",colorLight:"#fff"});');
    w.document.write('});');
    w.document.write('setTimeout(function(){window.print();window.close();},800);');
    w.document.write('};');
    w.document.write('<\/script>');
    w.document.write('</body></html>');
    w.document.close();
};