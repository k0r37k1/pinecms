// @ts-check
import { defineConfig } from 'eslint/config';
import js from '@eslint/js';
import pluginVue from 'eslint-plugin-vue';
import prettierConfig from 'eslint-config-prettier/flat';
import tseslint from 'typescript-eslint';
import globals from 'globals';

export default defineConfig([
    // Base ESLint recommended rules
    js.configs.recommended,

    // Vue 3 recommended rules
    ...pluginVue.configs['flat/recommended'],

    // General configuration
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
            // JavaScript Best Practices
            'no-console': ['warn', { allow: ['warn', 'error'] }],
            'no-debugger': 'warn',
            'no-unused-vars': ['error', { argsIgnorePattern: '^_' }],
            'prefer-const': 'error',
            'no-var': 'error',
            'object-shorthand': 'error',
            'quote-props': ['error', 'as-needed'],
            eqeqeq: ['error', 'always'],
            curly: ['error', 'all'],

            // Vue Rules
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
        },
    },

    // Vue-specific file configuration
    {
        files: ['**/*.vue'],
        rules: {
            'vue/html-indent': ['error', 4],
            'vue/script-indent': ['error', 4, { baseIndent: 0 }],
            'vue/max-attributes-per-line': [
                'error',
                {
                    singleline: 3,
                    multiline: 1,
                },
            ],
        },
    },

    // TypeScript-specific configuration (applies TS rules ONLY to .ts/.tsx files)
    ...tseslint.configs.recommended.map((config) => ({
        ...config,
        files: ['**/*.ts', '**/*.tsx'],
    })),
    {
        files: ['**/*.ts', '**/*.tsx'],
        languageOptions: {
            parser: tseslint.parser,
            parserOptions: {
                projectService: true,
                tsconfigRootDir: import.meta.dirname,
            },
        },
        rules: {
            // TypeScript-specific rules
            '@typescript-eslint/no-explicit-any': 'warn',
            '@typescript-eslint/no-unused-vars': ['error', { argsIgnorePattern: '^_' }],
            '@typescript-eslint/explicit-function-return-type': 'off',
            '@typescript-eslint/explicit-module-boundary-types': 'off',
            '@typescript-eslint/no-non-null-assertion': 'warn',

            // Disable base rule as it can report incorrect errors
            'no-unused-vars': 'off',
        },
    },

    // Ignores
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

    // Prettier config - MUST be last to override other configs
    prettierConfig,
]);
