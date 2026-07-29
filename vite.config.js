import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/header.css',
                'resources/css/home-page.css',
                'resources/css/footer.css',
                'resources/css/button.css',
                'resources/css/about-us.css',
                'resources/css/pages-shared.css',
                'resources/css/portfolio.css',
                'resources/css/pricing.css',
                'resources/css/process.css',
                'resources/css/services.css',
                'resources/css/video-modal.css',
                'resources/css/admin/index.css',
                'resources/js/app.js',
                'resources/js/pages/home.js',
                'resources/js/pages/portfolio.js',
                'resources/js/pages/about-us.js',
                'resources/js/pages/pricing.js',
                'resources/js/pages/process.js',
                'resources/js/pages/services.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
