import flatpickr from "flatpickr";
import { Indonesian } from "flatpickr/dist/l10n/id.js";
import 'flatpickr/dist/flatpickr.min.css';

flatpickr.localize(Indonesian);
window.flatpickr = flatpickr;

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import { membershipPackageStatus, membershipPackages } from './admin/membership-packages.js';
import paymentVerification from './admin/payment-verification.js';
import { ptPackageStatus, ptPackages } from './admin/pt-packages.js';

import './push-notifications.js';

window.Alpine = Alpine;

Alpine.data('pushNotificationToggle', () => ({
    supported: false,
    permission: 'default',
    isSubscribed: false,
    loading: false,

    async init() {
        this.supported = window.FitCorePush?.isSupported() ?? false;
        if (!this.supported) return;

        this.permission = window.FitCorePush.getPermission();
        this.isSubscribed = await window.FitCorePush.isSubscribed();
    },

    async toggle() {
        if (!this.supported) return;
        this.loading = true;
        try {
            if (this.isSubscribed) {
                await window.FitCorePush.unsubscribe();
                this.isSubscribed = false;
            } else {
                await window.FitCorePush.subscribe();
                this.isSubscribed = true;
                this.permission = window.FitCorePush.getPermission();
            }
        } catch (e) {
            console.error('[PushToggle] Error:', e);
            alert(e.message || 'Gagal mengubah pengaturan notifikasi push.');
        } finally {
            this.loading = false;
        }
    }
}));

Alpine.data('membershipPackages', membershipPackages);
Alpine.data('membershipPackageStatus', membershipPackageStatus);
Alpine.data('paymentVerification', paymentVerification);
Alpine.data('ptPackages', ptPackages);
Alpine.data('ptPackageStatus', ptPackageStatus);

Livewire.start();
