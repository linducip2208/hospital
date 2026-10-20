{{-- Service Worker registration + install prompt. Include sebelum </body>. --}}
<script>
(function () {
    // Jangan daftarkan SW dua kali
    if (window.__pwaRegisterDone) return;
    window.__pwaRegisterDone = true;

    function showInstallBtn() {
        var btn = document.getElementById('pwa-install-btn');
        if (btn) btn.classList.add('show');
    }
    function hideInstallBtn() {
        var btn = document.getElementById('pwa-install-btn');
        if (btn) btn.classList.remove('show');
    }

    // Sembunyikan tombol bila sudah dalam mode standalone
    try {
        if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true) {
            hideInstallBtn();
        }
    } catch (e) {}

    // Register Service Worker
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('/sw.js', { scope: '/' })
                .then(function (reg) {
                    // Auto-update SW saat ada versi baru
                    if (reg.waiting) {
                        promptUpdate(reg.waiting);
                        return;
                    }
                    reg.addEventListener('updatefound', function () {
                        var newWorker = reg.installing;
                        if (! newWorker) return;
                        newWorker.addEventListener('statechange', function () {
                            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                promptUpdate(newWorker);
                            }
                        });
                    });
                    // Cek update tiap 1 jam saat online
                    setInterval(function () { reg.update().catch(function () {}); }, 3600000);
                })
                .catch(function (e) {
                    console.warn('SW registration failed:', e);
                });
        });
    }

    function promptUpdate(worker) {
        if (confirm('Versi baru aplikasi tersedia. Refresh sekarang?')) {
            worker.postMessage({ type: 'SKIP_WAITING' });
            var reloaded = false;
            navigator.serviceWorker.addEventListener('controllerchange', function () {
                if (reloaded) return;
                reloaded = true;
                location.reload();
            });
            setTimeout(function () { location.reload(); }, 2500);
        }
    }

    // Install prompt handling
    var deferredPrompt = null;

    window.addEventListener('beforeinstallprompt', function (e) {
        e.preventDefault();
        deferredPrompt = e;
        showInstallBtn();
    });

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('#pwa-install-btn');
        if (! btn) return;
        if (! deferredPrompt) {
            // Fallback: beri tahu cara install manual (terutama iOS)
            var isIOS = /iphone|ipad|ipod/i.test(navigator.userAgent);
            alert(isIOS
                ? 'Untuk memasang: buka menu Bagikan di Safari lalu pilih "Add to Home Screen".'
                : 'Buka menu browser (⋮) lalu pilih "Install / Pasang aplikasi".');
            return;
        }
        e.preventDefault();
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then(function (choice) {
            if (choice.outcome === 'accepted') hideInstallBtn();
            deferredPrompt = null;
        }).catch(function () { deferredPrompt = null; });
    });

    window.addEventListener('appinstalled', function () {
        deferredPrompt = null;
        hideInstallBtn();
    });
})();
</script>
