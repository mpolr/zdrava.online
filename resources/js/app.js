import './bootstrap';

import * as Sentry from '@sentry/browser';
Sentry.init({
    dsn: import.meta.env.VITE_LARAVEL_FRONTEND_DSN,
    tracesSampleRate: 1.0,
    integrations: [Sentry.extraErrorDataIntegration()],
    initialScope: {
        tags: { "my-tag": "my value" },
        user: { id: window.user_id },
    },
});

import Alpine from 'alpinejs';

import 'flowbite';
import Toaster from '../../vendor/masmerise/livewire-toaster/resources/js';

// Импорт компонентов Alpine.js
import './map.js';

Alpine.plugin(Toaster);
window.Alpine = Alpine;
Alpine.start();
