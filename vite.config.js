import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/form/adminlte.min.css',
                'resources/css/form/all.min.css',
                'resources/js/app.js',
                'resources/js/form/jquery.min.js',
                'resources/js/form/bootstrap.bundle.min.js',
                'resources/js/form/bs-custom-file-input.min.js',
                'resources/js/form/adminlte.min.js',
                'resources/js/form/demo.js'
            ],
            refresh: true,
        }),
        vue(),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
