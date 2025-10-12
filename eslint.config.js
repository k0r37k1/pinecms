import js from '@eslint/js';
import pluginVue from 'eslint-plugin-vue';
import prettierConfig from 'eslint-config-prettier';
import globals from 'globals';

export default [
    js.configs.recommended,
    ...pluginVue.configs['flat/recommended'],
    prettierConfig,
    {
        languageOptions: {
            ecmaVersion: 'latest',
            sourceType: 'module',
            globals: {
                ...globals.browser,
                ...globals.node,
                ...globals.es2021,
            },
            parserOptions: {
                ecmaFeatures: {
                    jsx: true,
                },
            },
        },
        rules: {
            // JavaScript
            'no-console': ['warn', { allow: ['warn', 'error'] }],
            'no-debugger': 'warn',
            'no-unused-vars': ['error', { argsIgnorePattern: '^_' }],
            'prefer-const': 'error',
            'no-var': 'error',
            'object-shorthand': 'error',
            'quote-props': ['error', 'as-needed'],

            // Vue
            'vue/multi-word-component-names': 'off',
            'vue/no-v-html': 'warn',
            'vue/require-default-prop': 'error',
            'vue/require-prop-types': 'error',
            'vue/component-name-in-template-casing': ['error', 'PascalCase'],
            'vue/custom-event-name-casing': ['error', 'camelCase'],
            'vue/no-unused-components': 'warn',
            'vue/no-unused-vars': 'error',
            'vue/no-mutating-props': 'error',
            'vue/prefer-import-from-vue': 'error',

            // Best Practices
            'eqeqeq': ['error', 'always'],
            'curly': ['error', 'all'],
            'brace-style': ['error', '1tbs'],
            'comma-dangle': ['error', 'always-multiline'],
            'semi': ['error', 'always'],
            'quotes': ['error', 'single', { avoidEscape: true }],
        },
    },
    {
        files: ['**/*.vue'],
        rules: {
            'vue/html-indent': ['error', 4],
            'vue/script-indent': ['error', 4, { baseIndent: 0 }],
            'vue/max-attributes-per-line': ['error', {
                singleline: 3,
                multiline: 1,
            }],
        },
    },
    {
        ignores: [
            'vendor/**',
            'node_modules/**',
            'public/build/**',
            'public/hot',
            'storage/**',
            'bootstrap/cache/**',
            'coverage/**',
            'dist/**',
            '*.config.js',
        ],
    },
];
