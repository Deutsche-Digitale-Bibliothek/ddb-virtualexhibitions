importScripts("themes/ddb/javascripts/vendor/workbox/workbox-v5.1.3/workbox-sw.js");

if (workbox) {

  workbox.setConfig({
    // debug: true,
    clientsClaim: true,
    skipWaiting: true
  });

  const FALLBACK_IMAGE_URL = self.registration.scope + 'themes/ddb/images/offline.png';

  workbox.precaching.precacheAndRoute(self.__WB_MANIFEST);

  workbox.routing.registerRoute(
    /(.*)\.(?:png|gif|jpg|svg|ico|webp)/,
    new workbox.strategies.CacheFirst({
      cacheName: "images",
      plugins: [
        new workbox.expiration.ExpirationPlugin({
          maxEntries: 500,
          maxAgeSeconds: 365 * 24 * 60 * 60, // 1 Year
        })
      ]
    })
  );

  workbox.routing.registerRoute(
    /(.*)\.(?:eot|woff2|woff|woff2|ttf)/,
    new workbox.strategies.CacheFirst({
      cacheName: "fonts",
      plugins: [
        new workbox.expiration.ExpirationPlugin({
          maxEntries: 500,
          maxAgeSeconds: 365 * 24 * 60 * 60, // 1 Year
        })
      ]
    })
  );

  workbox.routing.registerRoute(
    /(.*)\.(?:mp3|ogg)/,
    new workbox.strategies.CacheFirst({
      cacheName: 'audio',
      plugins: [
        new workbox.cacheableResponse.CacheableResponsePlugin({statuses: [200]}),
        new workbox.rangeRequests.RangeRequestsPlugin()
      ]
    })
  );

  workbox.routing.registerRoute(
    /(.*)piwik\.(?:js|php)/,
    new workbox.strategies.NetworkOnly({
      cacheName: 'network',
      plugins: []
    })
  );

  workbox.routing.setDefaultHandler(new workbox.strategies.StaleWhileRevalidate());

  workbox.routing.setCatchHandler(({event}) => {
    switch (event.request.destination) {
      case 'image':
        return caches.match(FALLBACK_IMAGE_URL);
      default:
        return Response.error();
    }
  });

}
