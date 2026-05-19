const CACHE_VERSION = 'v1';
const STATIC_CACHE = `hapklaar-static-${CACHE_VERSION}`;
const PAGE_CACHE = `hapklaar-pages-${CACHE_VERSION}`;

const PRECACHE_URLS = [
    '/',
    '/manifest.json',
    '/icons/icon.svg',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then((cache) => cache.addAll(PRECACHE_URLS))
            .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((names) =>
            Promise.all(
                names
                    .filter((n) => n !== STATIC_CACHE && n !== PAGE_CACHE)
                    .map((n) => caches.delete(n))
            )
        ).then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    if (request.method !== 'GET') return;
    if (url.origin !== location.origin) return;

    // Skip Livewire wire requests
    if (url.pathname.startsWith('/livewire/')) return;

    // Skip admin panel
    if (url.pathname.startsWith('/admin')) return;

    // Cache-first: static assets (versioned builds, images, fonts, icons)
    if (
        url.pathname.startsWith('/build/') ||
        url.pathname.startsWith('/images/') ||
        url.pathname.startsWith('/fonts/') ||
        url.pathname.startsWith('/icons/')
    ) {
        event.respondWith(
            caches.open(STATIC_CACHE).then((cache) =>
                cache.match(request).then((cached) => {
                    if (cached) return cached;
                    return fetch(request).then((response) => {
                        if (response.ok) cache.put(request, response.clone());
                        return response;
                    });
                })
            )
        );
        return;
    }

    // Network-first: HTML pages — fall back to cache when offline
    event.respondWith(
        fetch(request)
            .then((response) => {
                if (response.ok) {
                    const clone = response.clone();
                    caches.open(PAGE_CACHE).then((cache) => cache.put(request, clone));
                }
                return response;
            })
            .catch(() =>
                caches.match(request).then((cached) => cached || caches.match('/'))
            )
    );
});
