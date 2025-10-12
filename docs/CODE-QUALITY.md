# Code Quality & Linting Guide

PineCMS uses multiple linters and formatters to maintain high code quality and
consistent styling across the codebase.

---

## Table of Contents

- [Overview](#overview)
- [ESLint](#eslint)
- [Prettier](#prettier)
- [Stylelint](#stylelint)
- [PHP Quality Tools](#php-quality-tools)
- [VS Code Setup](#vs-code-setup)
- [Pre-commit Hooks](#pre-commit-hooks)
- [Best Practices](#best-practices)
- [Troubleshooting](#troubleshooting)

---

## Overview

### Installed Tools

| Tool             | Version | Purpose                              |
| ---------------- | ------- | ------------------------------------ |
| **ESLint**       | 9.37.0  | JavaScript/Vue linting               |
| **Prettier**     | 3.6.2   | Code formatting (JS/Vue/CSS/JSON/MD) |
| **Stylelint**    | 16.25.0 | CSS/SCSS/Vue style linting           |
| **Laravel Pint** | 1.x     | PHP code formatting                  |
| **PHPStan**      | 2.0     | PHP static analysis                  |
| **Rector**       | 2.x     | PHP code refactoring                 |

### Quick Commands

```bash
# JavaScript/Vue
npm run lint         # Run ESLint
npm run lint:fix     # Fix ESLint issues
npm run format       # Format with Prettier

# CSS
npm run lint:css     # Run Stylelint
npm run lint:css:fix # Fix Stylelint issues

# PHP
composer format      # Format with Pint
composer analyse     # Run PHPStan
composer rector      # Preview Rector changes
```

---

## ESLint

### What is ESLint?

ESLint is a pluggable JavaScript linter that helps find and fix problems in your
code. It catches bugs, enforces coding standards, and improves code quality.

### Configuration

**File:** `eslint.config.js` (Flat Config - ESLint 9+)

**Features:**

- ✅ Modern flat configuration
- ✅ Vue 3 support with `eslint-plugin-vue`
- ✅ TypeScript support with `@typescript-eslint`
- ✅ Prettier integration
- ✅ Browser + Node globals
- ✅ Strict rules for best practices

### Plugins

```javascript
// Installed plugins
- @eslint/js              // Core ESLint rules
- eslint-plugin-vue       // Vue 3 specific rules
- @typescript-eslint/*    // TypeScript support
- eslint-config-prettier  // Disable conflicting Prettier rules
```

### Key Rules

**JavaScript:**

```javascript
'no-console': ['warn', { allow: ['warn', 'error'] }]  // Warn on console.log
'no-debugger': 'warn'                                  // Warn on debugger
'no-unused-vars': ['error', { argsIgnorePattern: '^_' }] // Error on unused vars
'prefer-const': 'error'                                // Enforce const
'no-var': 'error'                                      // No var allowed
```

**Vue:**

```javascript
'vue/multi-word-component-names': 'off'                // Allow single-word components
'vue/require-default-prop': 'error'                    // Require default props
'vue/require-prop-types': 'error'                      // Require prop types
'vue/component-name-in-template-casing': ['error', 'PascalCase']
'vue/no-mutating-props': 'error'                       // Prevent prop mutations
```

**TypeScript (for .ts/.tsx files):**

```javascript
'@typescript-eslint/no-explicit-any': 'warn'           // Warn on any type
'@typescript-eslint/no-unused-vars': 'error'          // Error on unused vars
'@typescript-eslint/no-non-null-assertion': 'warn'    // Warn on ! operator
```

### Running ESLint

```bash
# Lint all files
npm run lint

# Lint and auto-fix
npm run lint:fix

# Lint specific file
npx eslint resources/js/app.js

# Lint with auto-fix
npx eslint resources/js/app.js --fix
```

### ESLint in VS Code

Install the **ESLint extension** and add to `settings.json`:

```json
{
    "eslint.enable": true,
    "eslint.validate": [
        "javascript",
        "javascriptreact",
        "typescript",
        "typescriptreact",
        "vue"
    ],
    "editor.codeActionsOnSave": {
        "source.fixAll.eslint": "explicit"
    }
}
```

### Common ESLint Errors

**Error: `'variable' is assigned a value but never used`**

```javascript
// Bad
const unused = 'value';

// Good - prefix with _ if intentionally unused
const _unused = 'value';
```

**Error: `Component name should be multi-word`**

```vue
<!-- Bad -->
<script setup>
// Component named "App" or "Home"
</script>

<!-- Good -->
<script setup>
// Component named "AppLayout" or "HomePage"
</script>

<!-- Or disable the rule -->
<!-- eslint-disable-next-line vue/multi-word-component-names -->
```

---

## Prettier

### What is Prettier?

Prettier is an opinionated code formatter that enforces a consistent style by
parsing your code and reprinting it with its own rules.

### Configuration

**File:** `.prettierrc`

**Key Settings:**

```json
{
    "semi": true, // Always use semicolons
    "singleQuote": true, // Use single quotes
    "trailingComma": "all", // Trailing commas everywhere
    "tabWidth": 4, // 4 spaces indentation
    "printWidth": 100, // Max line length
    "arrowParens": "always", // Always parentheses in arrows
    "endOfLine": "lf", // Unix line endings
    "plugins": ["prettier-plugin-tailwindcss"] // Auto-sort Tailwind classes
}
```

### File-Specific Overrides

```json
{
    "overrides": [
        {
            "files": "*.vue",
            "options": { "parser": "vue" }
        },
        {
            "files": ["*.json", "*.jsonc"],
            "options": { "tabWidth": 2 }
        },
        {
            "files": ["*.yml", "*.yaml"],
            "options": { "tabWidth": 2 }
        },
        {
            "files": "*.md",
            "options": {
                "proseWrap": "always",
                "printWidth": 80
            }
        }
    ]
}
```

### Running Prettier

```bash
# Format all files
npm run format

# Check formatting without changing files
npm run format:check

# Format specific file
npx prettier resources/js/app.js --write

# Check specific file
npx prettier resources/js/app.js --check
```

### Prettier in VS Code

Install the **Prettier extension** and add to `settings.json`:

```json
{
    "editor.formatOnSave": true,
    "editor.defaultFormatter": "esbenp.prettier-vscode",
    "[javascript]": {
        "editor.defaultFormatter": "esbenp.prettier-vscode"
    },
    "[vue]": {
        "editor.defaultFormatter": "esbenp.prettier-vscode"
    },
    "[json]": {
        "editor.defaultFormatter": "esbenp.prettier-vscode"
    }
}
```

### Tailwind Class Sorting

Prettier automatically sorts Tailwind classes using
`prettier-plugin-tailwindcss`:

```vue
<!-- Before -->
<div class="mt-2 bg-blue-500 p-4 text-center"></div>
```

---

## Stylelint

### What is Stylelint?

Stylelint is a modern CSS linter that helps you avoid errors and enforce
conventions in your styles.

### Configuration

**File:** `stylelint.config.js`

**Extends:**

```javascript
extends: [
    'stylelint-config-standard',        // Standard CSS rules
    'stylelint-config-recommended-vue', // Vue SFC support
    'stylelint-config-tailwindcss',     // Tailwind compatibility
]
```

**Plugins:**

```javascript
plugins: [
    '@stylistic/stylelint-plugin', // Stylistic rules (v16+)
    'stylelint-order', // Property ordering
];
```

### Key Rules

**Stylistic:**

```javascript
'@stylistic/indentation': 4              // 4-space indentation
'@stylistic/string-quotes': 'single'     // Single quotes
'@stylistic/color-hex-case': 'lower'     // Lowercase hex colors
```

**Tailwind Support:**

```javascript
'at-rule-no-unknown': [
    true,
    {
        ignoreAtRules: [
            'tailwind',  // @tailwind base;
            'apply',     // @apply flex;
            'variants',  // @variants hover, focus;
            'responsive',
            'screen',
            'layer',
            'config',
        ],
    },
]
```

**Property Order:**

Stylelint enforces a logical property order:

```css
.element {
    /* Positioning */
    position: absolute;
    top: 0;
    left: 0;
    z-index: 10;

    /* Box Model */
    display: flex;
    width: 100px;
    height: 100px;
    margin: 10px;
    padding: 10px;

    /* Visual */
    border: 1px solid black;
    background: white;
    color: black;

    /* Typography */
    font-size: 16px;
    text-align: center;

    /* Misc */
    overflow: hidden;
    transition: all 0.3s;
}
```

### Running Stylelint

```bash
# Lint all CSS/Vue files
npm run lint:css

# Lint and auto-fix
npm run lint:css:fix

# Lint specific file
npx stylelint resources/css/app.css

# Lint with auto-fix
npx stylelint resources/css/app.css --fix
```

### Stylelint in VS Code

Install the **Stylelint extension** and add to `settings.json`:

```json
{
    "stylelint.enable": true,
    "stylelint.validate": ["css", "scss", "vue"],
    "editor.codeActionsOnSave": {
        "source.fixAll.stylelint": "explicit"
    }
}
```

---

## PHP Quality Tools

### Laravel Pint

**Opinionated PHP code formatter based on PHP-CS-Fixer**

```bash
# Format all PHP files
composer format

# Check formatting without changes
composer format:check

# Format specific file
./vendor/bin/pint app/Models/User.php
```

### PHPStan (Larastan)

**Static analysis tool for PHP**

```bash
# Analyze codebase
composer analyse

# Generate baseline (ignore existing errors)
composer analyse:baseline

# Analyze specific file
./vendor/bin/phpstan analyse app/Models/User.php
```

**Configuration:** `phpstan.neon`

### Rector

**Automated code refactoring tool**

```bash
# Preview changes
composer rector

# Apply changes
composer rector:fix

# Process specific file
./vendor/bin/rector process app/Models/User.php
```

**Configuration:** `rector.php`

---

## VS Code Setup

### Recommended Extensions

**Essential:**

- `dbaeumer.vscode-eslint` - ESLint integration
- `esbenp.prettier-vscode` - Prettier formatter
- `stylelint.vscode-stylelint` - Stylelint integration
- `Vue.volar` - Vue 3 language support

**PHP:**

- `bmewburn.vscode-intelephense-client` - PHP IntelliSense
- `shufo.vscode-blade-formatter` - Blade formatter

**Utilities:**

- `EditorConfig.EditorConfig` - EditorConfig support
- `usernamehw.errorlens` - Inline error display

### VS Code Settings

Create `.vscode/settings.json`:

```json
{
    // Editor
    "editor.formatOnSave": true,
    "editor.defaultFormatter": "esbenp.prettier-vscode",
    "editor.codeActionsOnSave": {
        "source.fixAll.eslint": "explicit",
        "source.fixAll.stylelint": "explicit"
    },

    // ESLint
    "eslint.enable": true,
    "eslint.validate": [
        "javascript",
        "javascriptreact",
        "typescript",
        "typescriptreact",
        "vue"
    ],

    // Stylelint
    "stylelint.enable": true,
    "stylelint.validate": ["css", "scss", "vue"],

    // Vue
    "[vue]": {
        "editor.defaultFormatter": "esbenp.prettier-vscode"
    },

    // PHP
    "[php]": {
        "editor.defaultFormatter": "bmewburn.vscode-intelephense-client",
        "editor.formatOnSave": false
    },

    // File associations
    "files.associations": {
        "*.blade.php": "blade"
    }
}
```

### Workspace Extensions

Create `.vscode/extensions.json`:

```json
{
    "recommendations": [
        "dbaeumer.vscode-eslint",
        "esbenp.prettier-vscode",
        "stylelint.vscode-stylelint",
        "Vue.volar",
        "bmewburn.vscode-intelephense-client",
        "shufo.vscode-blade-formatter",
        "EditorConfig.EditorConfig",
        "usernamehw.errorlens"
    ]
}
```

---

## Pre-commit Hooks

### Husky + lint-staged

PineCMS uses **Husky** for Git hooks and **lint-staged** to run linters on
staged files only.

**Configuration:** `package.json`

```json
{
    "lint-staged": {
        "*.{js,vue}": ["eslint --fix", "prettier --write"],
        "*.{css,scss,vue}": ["stylelint --fix"],
        "*.php": ["./vendor/bin/pint"],
        "*.{json,md}": ["prettier --write"]
    }
}
```

### What Happens on Commit?

1. **Staged files are detected**
2. **Linters run automatically:**
    - JavaScript/Vue → ESLint + Prettier
    - CSS/SCSS/Vue → Stylelint
    - PHP → Laravel Pint
    - JSON/Markdown → Prettier
3. **If all pass:** Commit proceeds
4. **If any fail:** Commit is blocked, fix issues and retry

### Bypass Hooks (Not Recommended)

```bash
# Skip pre-commit hooks (use with caution)
git commit --no-verify -m "fix: urgent hotfix"
```

---

## Best Practices

### JavaScript/Vue

1. **Always use `const` or `let`, never `var`**

    ```javascript
    // Good
    const name = 'John';
    let count = 0;

    // Bad
    var name = 'John';
    ```

2. **Prefix unused variables with underscore**

    ```javascript
    // Good
    const _unusedButIntentional = value;

    // Bad (ESLint error)
    const unused = value;
    ```

3. **Always define prop types**

    ```vue
    <script setup>
    // Good
    defineProps({
        title: {
            type: String,
            required: true,
        },
    });

    // Bad (ESLint error)
    defineProps(['title']);
    </script>
    ```

4. **Use PascalCase for components**

    ```vue
    <!-- Good -->
    <UserProfile />
    <FormInput />

    <!-- Bad -->
    <user-profile />
    <form-input />
    ```

### CSS/Styles

1. **Order properties logically**

    ```css
    /* Good - grouped by category */
    .element {
        position: relative;
        top: 0;
        display: flex;
        width: 100px;
        background: white;
        font-size: 16px;
    }
    ```

2. **Use single quotes**

    ```css
    /* Good */
    .element {
        content: 'text';
        background-image: url('image.jpg');
    }

    /* Bad */
    .element {
        content: 'text';
    }
    ```

3. **Lowercase hex colors**

    ```css
    /* Good */
    color: #fff;
    color: #ff5733;

    /* Bad */
    color: #fff;
    color: #ff5733;
    ```

### PHP

1. **Use strict types**

    ```php
    <?php

    declare(strict_types=1);

    namespace App\Models;
    ```

2. **Type everything**

    ```php
    // Good
    public function getName(): string
    {
        return $this->name;
    }

    // Bad
    public function getName()
    {
        return $this->name;
    }
    ```

3. **Use early returns**

    ```php
    // Good
    public function process(?User $user): void
    {
        if ($user === null) {
            return;
        }

        // Process user
    }

    // Bad
    public function process(?User $user): void
    {
        if ($user !== null) {
            // Process user
        }
    }
    ```

---

## Troubleshooting

### ESLint Issues

**Problem: "Parsing error: Unexpected token"**

```bash
# Solution: Clear ESLint cache
rm -rf node_modules/.cache
npm run lint -- --cache=false
```

**Problem: "Cannot find module"**

```bash
# Solution: Reinstall dependencies
rm -rf node_modules package-lock.json
npm install
```

**Problem: ESLint not running in VS Code**

```json
// Check ESLint output panel in VS Code
// View → Output → Select "ESLint" from dropdown
// Restart ESLint server: Cmd+Shift+P → "ESLint: Restart ESLint Server"
```

### Prettier Issues

**Problem: Prettier not formatting on save**

```json
// Check settings.json
{
    "editor.formatOnSave": true,
    "editor.defaultFormatter": "esbenp.prettier-vscode"
}
```

**Problem: Conflicting with ESLint**

```bash
# Prettier and ESLint should not conflict
# We use eslint-config-prettier to disable conflicting rules
# If issues persist, run: npm run lint:fix && npm run format
```

### Stylelint Issues

**Problem: "@tailwind is an unknown rule"**

```javascript
// Fixed in config: at-rule-no-unknown allows @tailwind
// If still occurring, check stylelint.config.js
```

**Problem: Stylelint not working in Vue files**

```bash
# Make sure stylelint-config-recommended-vue is installed
npm list stylelint-config-recommended-vue

# Reinstall if missing
npm install --save-dev stylelint-config-recommended-vue
```

### PHP Pint Issues

**Problem: Pint not found**

```bash
# Ensure Pint is installed
composer require laravel/pint --dev

# Run with full path
./vendor/bin/pint
```

**Problem: Pint changes revert after commit**

```bash
# Check .gitattributes for line ending issues
# Ensure lint-staged is configured correctly in package.json
```

---

## Resources

### Documentation

- [ESLint Docs](https://eslint.org/docs/latest/)
- [Prettier Docs](https://prettier.io/docs/en/)
- [Stylelint Docs](https://stylelint.io/)
- [Laravel Pint Docs](https://laravel.com/docs/12.x/pint)
- [PHPStan Docs](https://phpstan.org/)
- [Rector Docs](https://getrector.com/)

### Vue Specific

- [eslint-plugin-vue](https://eslint.vuejs.org/)
- [Vue Style Guide](https://vuejs.org/style-guide/)

### Tailwind

- [prettier-plugin-tailwindcss](https://github.com/tailwindlabs/prettier-plugin-tailwindcss)
- [stylelint-config-tailwindcss](https://github.com/ota-meshi/stylelint-config-tailwindcss)
