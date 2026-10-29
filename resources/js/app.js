import flatpickr from "flatpickr";
import { Indonesian } from "flatpickr/dist/l10n/id.js";
import 'flatpickr/dist/flatpickr.min.css';

flatpickr.localize(Indonesian);
window.flatpickr = flatpickr;

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import { membershipPackageStatus, membershipPackages } from './admin/membership-packages.js';
import paymentVerification from './admin/payment-verification.js';
import { ptPackageStatus, ptPackages } from './admin/pt-packages.js';

window.Alpine = Alpine;

Alpine.data('membershipPackages', membershipPackages);
Alpine.data('membershipPackageStatus', membershipPackageStatus);
Alpine.data('paymentVerification', paymentVerification);
Alpine.data('ptPackages', ptPackages);
Alpine.data('ptPackageStatus', ptPackageStatus);

Livewire.start();
