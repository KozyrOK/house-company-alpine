import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],

    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources'),
        },
    },

    server: {
        host: '0.0.0.0',
        port: 5174,
        strictPort: true,
        hmr: {
            host: '127.0.0.1',
            protocol: 'ws',
            port: 5174,
            clientPort: 5174,
        },
        cors: {
            origin: '*',
            credentials: true,
        },
    },

    build: {
        outDir: 'public/build',
        assetsDir: 'assets',
        manifest: true,
    },
});
