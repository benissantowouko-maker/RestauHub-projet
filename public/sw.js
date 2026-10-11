// RestauHub Service Worker v1.0
// Handles offline caching for the PWA (client + staff portals)

const CACHE_NAME = 'restaurhub-v1';
const STATIC_CACHE = 'restaurhub-static-v1';

// Assets to precache for offline use
const PRECACHE_URLS = [
  '/',
  '/menu',
  '/offline',
  '/manifest.json',
];

// Install: precache static shell
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(STATIC_CACHE).then((cache) => {
      return cache.addAll(PRECACHE_URLS).catch(() => {
        // Some resources may fail in dev; ignore gracefully
      });
    }).then(() => self.skipWaiting())
  );
});

// Activate: clean up old caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames
          .filter((name) => name !== CACHE_NAME && name !== STATIC_CACHE)
          .map((name) => caches.delete(name))
      );
    }).then(() => self.clients.claim())
  );
});

// Fetch: Network-first for API/dynamic, Cache-first for static assets
self.addEventListener('fetch', (event) => {
  const url = new URL(event.request.url);

  // Skip non-GET and cross-origin requests
  if (event.request.method !== 'GET') return;
  if (url.origin !== location.origin) return;

  // Skip admin/staff routes — always network (real-time critical)
  if (url.pathname.startsWith('/admin') ||
      url.pathname.startsWith('/cuisine') ||
      url.pathname.startsWith('/pro')) {
    return;
  }

  // API routes: network-first, no cache
  if (url.pathname.startsWith('/api/')) {
    event.respondWith(
      fetch(event.request).catch(() => {
        return new Response(JSON.stringify({ error: 'Hors ligne' }), {
          headers: { 'Content-Type': 'application/json' }
        });
      })
    );
    return;
  }

  // Static assets (CSS, JS, images, fonts): cache-first
  if (url.pathname.match(/\.(css|js|png|jpg|jpeg|webp|svg|woff2?|ico)$/)) {
    event.respondWith(
      caches.match(event.request).then((cached) => {
        return cached || fetch(event.request).then((response) => {
          if (response.ok) {
            const clone = response.clone();
            caches.open(STATIC_CACHE).then((cache) => cache.put(event.request, clone));
          }
          return response;
        });
      })
    );
    return;
  }

  // Pages: network-first, fallback to offline page
  event.respondWith(
    fetch(event.request)
      .then((response) => {
        if (response.ok) {
          const clone = response.clone();
          caches.open(CACHE_NAME).then((cache) => cache.put(event.request, clone));
        }
        return response;
      })
      .catch(() => {
        return caches.match(event.request).then((cached) => {
          return cached || caches.match('/offline');
        });
      })
  );
});

// Background sync for offline order submissions
self.addEventListener('sync', (event) => {
  if (event.tag === 'sync-order') {
    event.waitUntil(syncPendingOrders());
  }
});

async function syncPendingOrders() {
  // Placeholder — orders queued offline will be retried on reconnect
  const db = await openIndexedDB();
  const orders = await db.getAll('pending-orders');
  for (const order of orders) {
    try {
      await fetch('/commander', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(order),
      });
      await db.delete('pending-orders', order.id);
    } catch {}
  }
}

function openIndexedDB() {
  return new Promise((resolve, reject) => {
    const req = indexedDB.open('restaurhub-db', 1);
    req.onupgradeneeded = (e) => {
      e.target.result.createObjectStore('pending-orders', { keyPath: 'id', autoIncrement: true });
    };
    req.onsuccess = (e) => {
      const db = e.target.result;
      resolve({
        getAll: (store) => new Promise((res) => {
          const tx = db.transaction(store, 'readonly');
          tx.objectStore(store).getAll().onsuccess = (ev) => res(ev.target.result);
        }),
        delete: (store, id) => new Promise((res) => {
          const tx = db.transaction(store, 'readwrite');
          tx.objectStore(store).delete(id).onsuccess = () => res();
        }),
      });
    };
    req.onerror = reject;
  });
}
