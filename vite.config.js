import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/staff.js',
                'resources/js/student.js',],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        proxy: {
            '/api': {
            target: 'http://daf-mis.test',
            changeOrigin: true,
            credentials: 'include',
            followRedirects: true,
        },
        '/user': {
            target: 'http://daf-mis.test',
            changeOrigin: true,
            credentials: 'include',
            followRedirects: true,
        },
        '/register': {
            target: 'http://daf-mis.test',
            changeOrigin: true,
            credentials: 'include',
            followRedirects: true,
        },
        '/student': {
            target: 'http://daf-mis.test',
            changeOrigin: true,
            credentials: 'include',
            followRedirects: true,
        },
        '/staff': {
            target: 'http://daf-mis.test',
            changeOrigin: true,
            credentials: 'include',
            followRedirects: true,
        },
        '/login': {
            target: 'http://daf-mis.test',
            changeOrigin: true,
            credentials: 'include',
            followRedirects: true,
        },
        '/sanctum': {
            target: 'http://daf-mis.test',
            changeOrigin: true,
            credentials: 'include',
            followRedirects: true,
        },
        }
    }
});
