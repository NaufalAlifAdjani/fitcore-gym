self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('push', (event) => {
    if (!event.data) {
        return;
    }

    let payload = {};
    try {
        payload = event.data.json();
    } catch (e) {
        payload = {
            title: 'FitCore',
            body: event.data.text(),
        };
    }

    const title = payload.title || 'FitCore Gym & Fitness';
    const options = {
        body: payload.body || '',
        icon: payload.icon || '/images/logo/logo-icon.png',
        badge: payload.badge || '/favicon.png',
        vibrate: [200, 100, 200],
        data: {
            url: payload.data?.url || payload.action_url || payload.url || '/',
        },
        tag: payload.tag || 'fitcore-' + Date.now(),
        renotify: true,
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const targetUrl = event.notification.data?.url || '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            for (const client of clientList) {
                if (client.url === targetUrl && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
