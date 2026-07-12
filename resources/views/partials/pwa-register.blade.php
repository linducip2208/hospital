{{-- Service Worker registration + install prompt. Include sebelum </body>. --}}
<script>
(function () {
    // Register Service Worker
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('/sw.js', { scope: '/' })
                .then(function (reg) {
                    // Auto-update SW saat ada versi baru
                    reg.addEventListener('updatefound', function () {
                        var newWorker = reg.installing;
                        if (! newWorker) return;
                        newWorker.addEventListener('statechange', function () {
                            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                if (confirm('Versi baru aplikasi tersedia. Refresh sekarang?')) {
                                    newWorker.postMessage({ type: 'SKIP_WAITING' });
                                    location.reload();
                                }
                            }
                        });
                    });
                })
                .catch(function (e) {
                    console.warn('SW registration failed:', e);
                });
        });
    }

    // Install prompt handling
    var deferredPrompt = null;
    var installBtn = null;

    window.addEventListener('beforeinstallprompt', function (e) {
        e.preventDefault();
        deferredPrompt = e;
        installBtn = document.getElementById('pwa-install-btn');
        if (installBtn) installBtn.classList.add('show');
    });

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('#pwa-install-btn');
        if (! btn || ! deferredPrompt) return;
        e.preventDefault();
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then(function (choice) {
            if (choice.outcome === 'accepted') {
                btn.classList.remove('show');
            }
            deferredPrompt = null;
        });
    });

    window.addEventListener('appinstalled', function () {
        deferredPrompt = null;
        if (installBtn) installBtn.classList.remove('show');
    });
})();
</script>
