# Spell Checking mit CSpell

**Multi-Language Spell Checking für PineCMS (Deutsch & Englisch)**

---

## 📚 Übersicht

PineCMS verwendet **CSpell** für automatische Rechtschreibprüfung in Code, Dokumentation und Markdown-Dateien. Die Konfiguration unterstützt sowohl **Deutsch** als auch **Englisch**.

### Warum CSpell?

- ✅ Multi-Language Support (Deutsch + Englisch)
- ✅ Erkennt Laravel/Vue/PrimeVue-spezifische Begriffe
- ✅ Ignoriert Code-Patterns (URLs, Hex-Farben, UUIDs, etc.)
- ✅ Integriert in CI/CD und Pre-Commit Hooks
- ✅ VS Code Integration verfügbar

---

## 🚀 Installation

Die Pakete sind bereits installiert:

```bash
npm install --save-dev cspell @cspell/dict-de-de
```

---

## ⚙️ Konfiguration

**Konfigurationsdatei:** `cspell.config.cjs`

### Wichtige Einstellungen

```javascript
{
  version: '0.2',
  language: 'en,de',  // Deutsch + Englisch

  // Importiert deutsches Wörterbuch
  import: ['@cspell/dict-de-de/cspell-ext.json'],

  // Dateien die geprüft werden
  files: [
    '**/*.{js,ts,vue,php,md,json,yaml,yml}',
    'CLAUDE.md',
    '.claude/**/*.md',
    'docs/**/*.md',
  ],

  // Ignorierte Verzeichnisse
  ignorePaths: [
    'node_modules/**',
    'vendor/**',
    'storage/**',
    'public/build/**',
    // ...
  ],
}
```

### Custom Dictionaries

Projektspezifische Begriffe werden in separaten Dictionary-Dateien verwaltet:

- **`dictionaries/laravel.txt`** - Laravel/PHP-spezifische Begriffe
- **`dictionaries/vue.txt`** - Vue/Frontend-spezifische Begriffe

**Beispiel hinzufügen eines neuen Begriffs:**

```txt
# dictionaries/vue.txt
newcomponent
customdirective
```

---

## 🎯 Verwendung

### NPM Scripts

```bash
# Rechtschreibprüfung durchführen
npm run spell

# Rechtschreibprüfung (ohne Progress-Anzeige)
npm run spell:check

# Vollständiger Quality-Check (inkl. Rechtschreibung)
npm run quality
```

### Manuelle Prüfung

```bash
# Einzelne Datei prüfen
npx cspell path/to/file.md

# Bestimmte Dateien prüfen
npx cspell "resources/**/*.vue"

# Mit Verbose-Output
npx cspell --verbose .
```

---

## 🔧 Integration

### 1. Pre-Commit Hooks (Husky + lint-staged)

CSpell läuft automatisch bei jedem `git commit`:

```json
{
  "lint-staged": {
    "*.{js,ts,vue}": ["cspell --no-must-find-files"],
    "*.{md,json,yaml,yml}": ["cspell --no-must-find-files"],
    "*.php": ["cspell --no-must-find-files"]
  }
}
```

### 2. Quality Workflow

```bash
npm run quality
```

Dieser Befehl führt aus:

1. Code-Formatierung (Prettier)
2. Linting (ESLint, Stylelint)
3. Type-Checking (TypeScript)
4. **Rechtschreibprüfung (CSpell)**
5. Tests (Vitest)

### 3. VS Code Integration

**Empfohlene Extension:**

- [Code Spell Checker](https://marketplace.visualstudio.com/items?itemName=streetsidesoftware.code-spell-checker)
- [German - Code Spell Checker](https://marketplace.visualstudio.com/items?itemName=streetsidesoftware.code-spell-checker-german)

**Installation:**

```bash
code --install-extension streetsidesoftware.code-spell-checker
code --install-extension streetsidesoftware.code-spell-checker-german
```

Die Extension liest automatisch die `cspell.config.cjs`.

---

## 📝 Best Practices

### 1. Projektspezifische Begriffe hinzufügen

**Option A: Zur Hauptkonfiguration (`cspell.config.cjs`)**

```javascript
words: [
  // Füge hier neue Begriffe hinzu
  'newterm',
  'customword',
]
```

**Option B: Zu Custom Dictionaries**

```bash
# Zu dictionaries/laravel.txt oder dictionaries/vue.txt hinzufügen
echo "newpackage" >> dictionaries/laravel.txt
```

### 2. Inline Ignoring

**In Markdown/Kommentaren:**

```markdown
<!-- cSpell:disable -->
Dieser Text wird nicht geprüft.
<!-- cSpell:enable -->

<!-- cSpell:ignore specialword anotherword -->
Diese specialword und anotherword werden ignoriert.
```

**In Code:**

```javascript
// cSpell:ignore complexvar unusualname
const complexvar = 'value';
```

### 3. Pattern-basiertes Ignoring

Die Konfiguration ignoriert bereits:

- ✅ Hex-Farben (`#RRGGBB`)
- ✅ UUIDs
- ✅ URLs und E-Mail-Adressen
- ✅ Base64-Strings
- ✅ SHA-Hashes
- ✅ Dateipfade
- ✅ Versionsnummern
- ✅ Umgebungsvariablen
- ✅ PHP-Variablen (`$variable`)
- ✅ Vue-Direktiven (`v-bind`, `v-if`)
- ✅ camelCase/PascalCase (teilweise)

---

## 🐛 Troubleshooting

### Problem: Zu viele False Positives

**Lösung:**

1. Füge Begriff zu `words` Array in `cspell.config.cjs` hinzu
2. Oder füge zu `dictionaries/laravel.txt` / `dictionaries/vue.txt` hinzu
3. Oder verwende inline ignoring (`cSpell:ignore`)

### Problem: CSpell findet gültige Wörter nicht

**Lösung:**

1. Prüfe ob das deutsche Wörterbuch korrekt importiert ist:

   ```javascript
   import: ['@cspell/dict-de-de/cspell-ext.json']
   ```

2. Prüfe die `language` Einstellung:

   ```javascript
   language: 'en,de'
   ```

3. Prüfe `languageSettings` für dateitypspezifische Konfiguration

### Problem: CSpell ist zu langsam

**Lösung:**

1. Verwende `--no-progress` Flag:

   ```bash
   npm run spell:check
   ```

2. Erhöhe die `ignorePaths` Liste in der Konfiguration

3. Verwende `--must-find-files` nur wenn nötig

---

## 🔍 Erweiterte Konfiguration

### Sprachspezifische Settings

```javascript
languageSettings: [
  {
    languageId: 'php',
    locale: 'en',
    dictionaries: ['php', 'laravel'],
  },
  {
    languageId: 'markdown',
    locale: 'en,de',  // Deutsch + Englisch für Markdown
    dictionaries: ['softwareTerms'],
  },
]
```

### Case Sensitivity

```javascript
{
  caseSensitive: false,  // Groß-/Kleinschreibung ignorieren
}
```

### Minimale Wortlänge

```javascript
{
  minWordLength: 3,  // Wörter < 3 Zeichen ignorieren
}
```

---

## 📚 Referenzen

- **Offizielle Dokumentation:** <https://cspell.org/docs/Configuration>
- **German Dictionary:** <https://www.npmjs.com/package/@cspell/dict-de-de>
- **VS Code Extension:** <https://marketplace.visualstudio.com/items?itemName=streetsidesoftware.code-spell-checker>

---

## 🎯 Quick Reference

```bash
# Prüfung durchführen
npm run spell

# Einzelne Datei prüfen
npx cspell path/to/file.md

# Wort zur Konfiguration hinzufügen
# → Editiere cspell.config.cjs → words: [...]

# Wort inline ignorieren
<!-- cSpell:ignore wordname -->

# Vollständiger Quality-Check
npm run quality
```

---

**Letzte Aktualisierung:** 2025-11-05
**Konfigurationsdatei:** `cspell.config.cjs`
**Custom Dictionaries:** `dictionaries/laravel.txt`, `dictionaries/vue.txt`
