const CACHE_NAME = 'erapor-cache-v1';

// Aset statis yang akan di-cache saat proses install
const PRECACHE_ASSETS = [
    '/manifest.json',
    '/favicon.ico',
    // Karena aplikasi ini sangat dinamis, kita hanya cache halaman offline atau asset dasar
];

// Event Install: Caching asset dasar
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(PRECACHE_ASSETS);
        })
    );
    self.skipWaiting();
});

// Event Activate: Membersihkan cache lama jika ada versi baru
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cache) => {
                    if (cache !== CACHE_NAME) {
                        return caches.delete(cache);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

// Event Fetch: Network First, fallback ke Cache
// Pendekatan ini aman untuk aplikasi dinamis seperti e-Rapor
self.addEventListener('fetch', (event) => {
    // Abaikan permintaan non-GET atau request ke API/Dapodik eksternal
    if (event.request.method !== 'GET' || !event.request.url.startsWith(self.location.origin)) {
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then((networkResponse) => {
                // Simpan salinan ke cache untuk mode offline
                return caches.open(CACHE_NAME).then((cache) => {
                    cache.put(event.request, networkResponse.clone());
                    return networkResponse;
                });
            })
            .catch(() => {
                // Jika jaringan gagal (offline), coba ambil dari cache
                return caches.match(event.request).then((cachedResponse) => {
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    // Jika tidak ada di cache, mungkin arahkan ke halaman offline.html (opsional)
                });
            })
    );
});
