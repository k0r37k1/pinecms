/**
 * CSpell Configuration
 * Spell checking for multi-language (English & German) Laravel + Vue project
 *
 * @see https://cspell.org/docs/Configuration
 */

/** @type {import('@cspell/cspell-types').CSpellSettings} */
module.exports = {
    version: '0.2',
    language: 'en,de',

    // Import German dictionary
    import: ['@cspell/dict-de-de/cspell-ext.json'],

    // Files to check
    files: [
        '**/*.{js,ts,vue,php,md,json,yaml,yml}',
        'CLAUDE.md',
        '.claude/**/*.md',
        'docs/**/*.md',
    ],

    // Files and directories to ignore
    ignorePaths: [
        'node_modules/**',
        'vendor/**',
        'storage/**',
        'bootstrap/cache/**',
        'public/build/**',
        'public/hot',
        '.git/**',
        '*.min.js',
        '*.min.css',
        'package.json',
        'package-lock.json',
        'composer.json',
        'composer.lock',
        'coverage/**',
        'test-results/**',
        '.vscode/**',
        '.idea/**',
        '.cursor/**',
        '.github/**',
        'playwright-report/**',
        '*.backup',
        '*.log',
        'docker/**',
        'sail',
        '.env.example',
        '.gitattributes',
        '.gitignore',
        'phpunit.xml',
        'phpstan.neon',
        'phpstan-baseline.neon',
        'pint.json',
        'infection.json5',
        'vite.config.js',
        'playwright.config.js',
        'public/.htaccess',
        '.coderabbit.yaml',
        '.claude-hooks-config.sh',
        '.claude/hooks/**',
        '.claude/skill-rules.json',
        'dictionaries/**',
        'cspell.config.cjs',
        'deptrac.yaml',
        'codecov.yml',
        'config/**',
        'database/migrations/**',
        'artisan',
        'resources/views/**',
    ],

    // Custom project dictionary
    // Note: Most words have been moved to ./dictionaries/*.txt for easier maintenance
    // Add new words directly to the dictionary files:
    //   - ./dictionaries/custom-words.txt (general project words)
    //   - ./dictionaries/laravel.txt (Laravel/PHP specific)
    //   - ./dictionaries/vue.txt (Vue/Frontend specific)
    words: [
        // Only temporary or emergency words here
        // Most words are now in ./dictionaries/*.txt files
    ],

    // Words to ignore (won't show as errors)
    ignoreWords: [
        // Code-specific patterns handled by regexes
    ],

    // RegEx patterns to ignore
    ignoreRegExpList: [
        // Hex colors (#RRGGBB, #RGB)
        /#[0-9a-fA-F]{3,8}\b/g,

        // UUIDs
        /[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/gi,

        // URLs and email addresses
        /https?:\/\/[^\s]+/g,
        /[\w.-]+@[\w.-]+\.\w+/g,

        // Base64 strings
        /[A-Za-z0-9+/]{20,}={0,2}/g,

        // SHA hashes
        /\b[0-9a-f]{40,}\b/gi,

        // File paths (Unix/Windows)
        /[/.\\][a-zA-Z0-9_\-./\\]+/g,

        // Version numbers
        /v?\d+\.\d+\.\d+(-[a-zA-Z0-9.]+)?/g,

        // Environment variables
        /[A-Z_]{3,}/g,

        // PHP/Laravel specific
        /\$[a-zA-Z_][a-zA-Z0-9_]*/g, // PHP variables
        /@[a-zA-Z]+\(/g, // PHP annotations
        /\b[a-z]+_[a-z_]+\(/g, // snake_case functions

        // Vue specific
        /v-[a-z]+/g, // Vue directives
        /:([a-z-]+)=/g, // Vue props binding
        /@([a-z-]+)=/g, // Vue event binding

        // Common code patterns
        /\b[A-Z][a-z]+[A-Z][a-z]+\b/g, // PascalCase
        /\b[a-z]+[A-Z][a-z]+\b/g, // camelCase
    ],

    // Language-specific settings
    languageSettings: [
        {
            languageId: '*',
            locale: 'en,de',
            dictionaries: ['custom-words', 'companies', 'softwareTerms', 'node', 'npm'],
        },
        {
            languageId: 'php',
            locale: 'en',
            dictionaries: ['custom-words', 'php', 'laravel'],
            ignoreRegExpList: [
                // PHP namespaces
                /namespace\s+[A-Za-z\\]+;/g,
                // Use statements
                /use\s+[A-Za-z\\]+;/g,
            ],
        },
        {
            languageId: 'javascript,typescript',
            locale: 'en',
            dictionaries: ['custom-words', 'typescript', 'node', 'npm'],
        },
        {
            languageId: 'vue',
            locale: 'en,de',
            dictionaries: ['custom-words', 'vue', 'html', 'css'],
        },
        {
            languageId: 'markdown',
            locale: 'en,de',
            dictionaries: ['custom-words', 'laravel', 'vue', 'softwareTerms'],
        },
    ],

    // Dictionary definitions
    dictionaryDefinitions: [
        {
            name: 'custom-words',
            path: './dictionaries/custom-words.txt',
            addWords: true,
        },
        {
            name: 'laravel',
            path: './dictionaries/laravel.txt',
            addWords: true,
        },
        {
            name: 'vue',
            path: './dictionaries/vue.txt',
            addWords: true,
        },
    ],

    // Enable case sensitivity
    caseSensitive: false,

    // Minimum word length to check
    minWordLength: 3,

    // Maximum number of problems to report per file
    maxNumberOfProblems: 100,

    // Maximum number of duplicate problems per file
    maxDuplicateProblems: 5,

    // Allow compound words
    allowCompoundWords: true,

    // Show suggestions
    suggestWords: true,
};
