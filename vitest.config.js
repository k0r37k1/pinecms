import { defineConfig } from 'vitest/config';
import vue from '@vitejs/plugin-vue';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
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

    test: {
        globals: true,
        environment: 'jsdom',
        setupFiles: ['./tests/setup.js'],
        include: [
            'resources/js/**/*.{test,spec}.{js,ts}',
            'tests/JavaScript/**/*.{test,spec}.{js,ts}',
        ],
        exclude: ['**/node_modules/**', '**/dist/**', '**/build/**', '**/vendor/**'],
        coverage: {
            provider: 'v8',
            reporter: ['text', 'lcov', 'html', 'clover'],
            reportsDirectory: './coverage',
            include: ['resources/js/**/*.{js,ts,vue}'],
            exclude: [
                '**/*.config.{js,ts}',
                '**/node_modules/**',
                '**/dist/**',
                '**/build/**',
                '**/vendor/**',
                '**/*.d.ts',
                '**/*.spec.{js,ts}',
                '**/*.test.{js,ts}',
                '**/tests/**',
            ],
            lines: 80,
            functions: 80,
            branches: 80,
            statements: 80,
        },
    },
});
