

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import flatpickr from "flatpickr";
import { Indonesian } from "flatpickr/dist/l10n/id.js";
import 'flatpickr/dist/flatpickr.min.css';

flatpickr.localize(Indonesian);
window.flatpickr = flatpickr;
