function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding)
        .replace(/-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

export const FitCorePush = {
    isSupported() {
        return 'serviceWorker' in navigator && 'PushManager' in window && 'Notification' in window;
    },

    getPermission() {
        if (!('Notification' in window)) {
            return 'unsupported';
        }
        return Notification.permission; // 'default', 'granted', 'denied'
    },

    async registerServiceWorker() {
        if (!this.isSupported()) {
            return null;
        }
        try {
            return await navigator.serviceWorker.register('/sw.js');
        } catch (error) {
            console.warn('[FitCore Push] Service Worker registration failed:', error);
            return null;
        }
    },

    async getSubscription() {
        const registration = await this.registerServiceWorker();
        if (!registration) {
            return null;
        }
        return await registration.pushManager.getSubscription();
    },

    async isSubscribed() {
        const sub = await this.getSubscription();
        return sub !== null && this.getPermission() === 'granted';
    },

    async subscribe() {
        if (!this.isSupported()) {
            throw new Error('Push notifications tidak didukung oleh browser Anda.');
        }

        const permission = await Notification.requestPermission();
        if (permission !== 'granted') {
            throw new Error('Izin notifikasi ditolak oleh pengguna.');
        }

        const registration = await this.registerServiceWorker();
        if (!registration) {
            throw new Error('Gagal mendaftarkan service worker.');
        }

        const response = await fetch('/push-subscriptions/vapid-public-key', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        if (!response.ok) {
            throw new Error('Gagal mengambil public key VAPID server.');
        }

        const { publicKey } = await response.json();
        if (!publicKey) {
            throw new Error('VAPID public key kosong.');
        }

        const convertedKey = urlBase64ToUint8Array(publicKey);

        const subscription = await registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: convertedKey,
        });

        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const subData = subscription.toJSON();

        const saveResponse = await fetch('/push-subscriptions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                endpoint: subData.endpoint,
                keys: subData.keys,
                content_encoding: (window.PushManager?.supportedContentEncodings || ['aes128gcm'])[0],
            }),
        });

        if (!saveResponse.ok) {
            throw new Error('Gagal menyimpan langganan notifikasi ke server.');
        }

        return subscription;
    },

    async unsubscribe() {
        const subscription = await this.getSubscription();
        if (!subscription) {
            return true;
        }

        const endpoint = subscription.endpoint;
        await subscription.unsubscribe();

        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        await fetch('/push-subscriptions', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ endpoint }),
        });

        return true;
    },
};

window.FitCorePush = FitCorePush;

// Auto register Service Worker in background when supported
if (FitCorePush.isSupported()) {
    FitCorePush.registerServiceWorker();
}
