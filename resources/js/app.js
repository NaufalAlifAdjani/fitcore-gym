

import Alpine from 'alpinejs';
import { membershipPackageStatus, membershipPackages } from './admin/membership-packages.js';
import paymentVerification from './admin/payment-verification.js';

window.Alpine = Alpine;

Alpine.data('membershipPackages', membershipPackages);
Alpine.data('membershipPackageStatus', membershipPackageStatus);
Alpine.data('paymentVerification', paymentVerification);

Alpine.start();
