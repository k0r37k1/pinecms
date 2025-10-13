export default {
    extends: [
        'stylelint-config-standard',
        'stylelint-config-recommended-vue',
        'stylelint-config-tailwindcss',
    ],
    plugins: ['@stylistic/stylelint-plugin', 'stylelint-order'],
    rules: {
        // Base Rules (using @stylistic plugin)
        '@stylistic/indentation': 4,
        '@stylistic/string-quotes': 'single',
        '@stylistic/color-hex-case': 'lower',
        'selector-pseudo-class-no-unknown': [
            true,
            {
                ignorePseudoClasses: ['global', 'deep'],
            },
        ],
        'selector-pseudo-element-no-unknown': [
            true,
            {
                ignorePseudoElements: ['v-deep'],
            },
        ],

        // At-rule (TailwindCSS v4.1 + legacy v3 support)
        'at-rule-no-unknown': [
            true,
            {
                ignoreAtRules: [
                    // TailwindCSS v4 at-rules
                    'plugin',
                    'theme',
                    'utility',
                    'config',
                    'reference',
                    'custom-variant',
                    // TailwindCSS v3 legacy at-rules (still supported)
                    'tailwind',
                    'apply',
                    'variants',
                    'responsive',
                    'screen',
                    'layer',
                ],
            },
        ],

        // Property Order
        'order/properties-order': [
            'position',
            'top',
            'right',
            'bottom',
            'left',
            'z-index',
            'display',
            'flex',
            'flex-direction',
            'flex-wrap',
            'align-items',
            'justify-content',
            'width',
            'height',
            'margin',
            'padding',
            'border',
            'background',
            'color',
            'font',
            'text-align',
            'overflow',
            'transition',
            'transform',
        ],

        // Disable rules that conflict with Tailwind
        'no-descending-specificity': null,
        'function-no-unknown': [
            true,
            {
                ignoreFunctions: ['theme', 'screen'],
            },
        ],
    },
    ignoreFiles: [
        'vendor/**/*',
        'node_modules/**/*',
        'public/build/**/*',
        'storage/**/*',
        'coverage/**/*',
        '**/*.js',
        '**/*.ts',
        '**/*.php',
    ],
};
