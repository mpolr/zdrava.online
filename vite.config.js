import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import manifestSRI from 'vite-plugin-manifest-sri';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/ckeditor.css',
                'resources/js/app.js',
                'resources/js/chart.js',
                'resources/js/ant.js',
                'resources/js/ckeditor.js',
            ],
            refresh: true,
        }),
        manifestSRI(),
    ],
});
