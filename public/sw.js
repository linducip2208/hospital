// ─────────────────────────────────────────────────────────
// Service Worker untuk SIMRS Hospital PWA
// Strategy: cache-first untuk static assets, network-first untuk page/API
// ─────────────────────────────────────────────────────────

const CACHE_VERSION = 'simrs-v1.0.0';
const STATIC_CACHE = `${CACHE_VERSION}-static`;
const DYNAMIC_CACHE = `${CACHE_VERSION}-dynamic`;

// Asset wajib di-cache saat install
const STATIC_ASSETS = [
    '/',
    '/offline.html',
    '/manifest.webmanifest',
    '/icons/icon-192.png',
    '/icons/icon-512.png',
    '/icons/icon.svg',
];

// CDN assets — cache once
const CDN_PATTERNS = [
    /^https:\/\/cdn\.jsdelivr\.net/,
    /^https:\/\/fonts\.bunny\.net/,
    /^https:\/\/images\.unsplash\.com/,
];

// Install: precache static assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then((cache) => cache.addAll(STATIC_ASSETS))
            .then(() => self.skipWaiting())
    );
});

// Activate: clean old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(
                keys
                    .filter((k) => !k.startsWith(CACHE_VERSION))
                    .map((k) => caches.delete(k))
            )
        ).then(() => self.clients.claim())
    );
});

// Fetch: routing strategy
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET dan non-HTTP(S)
    if (request.method !== 'GET' || !url.protocol.startsWith('http')) return;

    // Skip auth/admin sensitive routes — selalu network
    if (url.pathname.startsWith('/login')
        || url.pathname.startsWith('/logout')
        || url.pathname.startsWith('/__pair')
        || url.pathname.startsWith('/api/')) {
        return; // biarkan browser handle normal
    }

    // CDN assets — cache-first
    if (CDN_PATTERNS.some((re) => re.test(request.url))) {
        event.respondWith(cacheFirst(request, DYNAMIC_CACHE));
        return;
    }

    // Static asset Laravel build — cache-first
    if (url.pathname.startsWith('/build/')
        || url.pathname.startsWith('/icons/')
        || url.pathname.match(/\.(css|js|woff2?|ttf|eot|svg|png|jpg|jpeg|webp|ico|gif)$/i)) {
        event.respondWith(cacheFirst(request, STATIC_CACHE));
        return;
    }

    // HTML page — network-first dengan fallback offline
    if (request.headers.get('accept')?.includes('text/html')) {
        event.respondWith(networkFirstWithOfflineFallback(request));
        return;
    }

    // Default: network-first
    event.respondWith(networkFirst(request, DYNAMIC_CACHE));
});

// ─── Strategy implementations ───

async function cacheFirst(request, cacheName) {
    const cache = await caches.open(cacheName);
    const cached = await cache.match(request);
    if (cached) return cached;
    try {
        const response = await fetch(request);
        if (response.ok) cache.put(request, response.clone());
        return response;
    } catch (e) {
        return cached || new Response('Offline', { status: 503 });
    }
}

async function networkFirst(request, cacheName) {
    const cache = await caches.open(cacheName);
    try {
        const response = await fetch(request);
        if (response.ok) cache.put(request, response.clone());
        return response;
    } catch (e) {
        const cached = await cache.match(request);
        return cached || new Response('Offline', { status: 503 });
    }
}

async function networkFirstWithOfflineFallback(request) {
    const cache = await caches.open(DYNAMIC_CACHE);
    try {
        const response = await fetch(request);
        if (response.ok) cache.put(request, response.clone());
        return response;
    } catch (e) {
        const cached = await cache.match(request);
        if (cached) return cached;
        const offline = await caches.match('/offline.html');
        return offline || new Response('Offline', { status: 503 });
    }
}

// Listen ke pesan dari client untuk skip waiting saat update
self.addEventListener('message', (event) => {
    if (event.data?.type === 'SKIP_WAITING') self.skipWaiting();
});
