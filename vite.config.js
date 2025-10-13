import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        tailwindcss(),
    ],

    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/js'),
            '@components': resolve(__dirname, 'resources/js/Components'),
            '@pages': resolve(__dirname, 'resources/js/Pages'),
            '@layouts': resolve(__dirname, 'resources/js/Layouts'),
            '@stores': resolve(__dirname, 'resources/js/Stores'),
            '@composables': resolve(__dirname, 'resources/js/Composables'),
            '@utils': resolve(__dirname, 'resources/js/Utils'),
        },
    },

    build: {
        outDir: 'public/build',
        emptyOutDir: true,
        manifest: true,
        rollupOptions: {
            output: {
                manualChunks: {
                    'vue-vendor': ['vue'],
                    inertia: ['@inertiajs/vue3'],
                    vueuse: ['@vueuse/core'],
                },
            },
        },
        sourcemap: process.env.NODE_ENV === 'development',
        minify: process.env.NODE_ENV === 'production' ? 'terser' : false,
        terserOptions: {
            compress: {
                drop_console: process.env.NODE_ENV === 'production',
            },
        },
    },

    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: false,
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: false,
        },
    },

    optimizeDeps: {
        include: ['vue', '@inertiajs/vue3', '@vueuse/core', 'axios'],
    },

    css: {
        postcss: './postcss.config.js',
    },
});
