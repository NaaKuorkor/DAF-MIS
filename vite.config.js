import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        proxy: {
            '/api': 'http://daf-mis.test',
            '/user': 'http://daf-mis.test',
            '/student': 'http://daf-mis.test',
            '/staff': 'http://daf-mis.test',
            '/login': 'http://daf-mis.test',
            '/sanctum': 'http://daf-mis.test',
        }
    }
});
