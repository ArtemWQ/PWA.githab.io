const CACHE_NAME = 'bike-selector-v1';
const urlsToCache = [
  '/',
  '/index.php',
  '/catalog.php',
  '/search.php',
  '/result.php',
  '/assets/css/style.css',
  '/assets/js/script.js',
  '/assets/images/mountain.png',
  '/assets/images/road.png',
  '/assets/images/city.png',
  // Добавьте другие важные ресурсы
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        if (response) {
          return response;
        }
        return fetch(event.request);
      })
  );
});

self.addEventListener('activate', event => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});