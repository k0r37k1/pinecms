# Localization Guide

PineCMS uses a dual-layer localization system for complete multi-language support:

- **vue-i18n** (Frontend) - For UI translations
- **Laravel Translatable** (Backend) - For database content translations

---

## Frontend Translations (vue-i18n)

### Usage in Vue Components

```vue
<template>
    <div>
        <!-- Using $t() function -->
        <h1>{{ $t('app.name') }}</h1>
        <p>{{ $t('app.tagline') }}</p>

        <!-- With variables -->
        <p>{{ $t('messages.welcome', { name: userName }) }}</p>

        <!-- Buttons -->
        <button>{{ $t('actions.save') }}</button>
        <button>{{ $t('actions.cancel') }}</button>
    </div>
</template>

<script setup>
// Use Composition API
import { useI18n } from 'vue-i18n';

const { t, locale } = useI18n();

// Access translations
console.log(t('app.name')); // "PineCMS"

// Get current locale
console.log(locale.value); // "en" or "de"
</script>
```

### Adding New Translations

Edit locale files in `resources/js/locales/`:

**resources/js/locales/en.json:**
```json
{
    "app": {
        "name": "PineCMS"
    },
    "messages": {
        "welcome": "Hello, {name}!"
    }
}
```

**resources/js/locales/de.json:**
```json
{
    "app": {
        "name": "PineCMS"
    },
    "messages": {
        "welcome": "Hallo, {name}!"
    }
}
```

### Switching Locales (Frontend + Backend)

Use the `useLocale()` composable:

```vue
<script setup>
import { useLocale } from '@/Composables/useLocale';

const { currentLocale, supportedLocales, switchLocale } = useLocale();

// Switch to German
switchLocale('de');
</script>
```

This automatically:
1. Updates vue-i18n locale (frontend)
2. Sends POST request to `/locale` (backend)
3. Updates Laravel session

---

## Backend Translations (Laravel Translatable)

### Setup a Translatable Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class BlogPost extends Model
{
    use HasTranslations;

    // Define which attributes are translatable
    public $translatable = ['title', 'content', 'excerpt'];

    protected $fillable = ['title', 'content', 'excerpt', 'published_at'];
}
```

### Creating Translatable Content

```php
use App\Models\BlogPost;

// Create with translations
$post = BlogPost::create([
    'title' => [
        'en' => 'My First Blog Post',
        'de' => 'Mein erster Blog-Beitrag'
    ],
    'content' => [
        'en' => 'Welcome to my blog...',
        'de' => 'Willkommen auf meinem Blog...'
    ],
    'excerpt' => [
        'en' => 'A short summary',
        'de' => 'Eine kurze Zusammenfassung'
    ]
]);

// Update translation for specific locale
$post->setTranslation('title', 'de', 'Neuer Titel');
$post->save();
```

### Retrieving Translated Content

```php
use App\Models\BlogPost;

// Get post with current locale
app()->setLocale('de');
$post = BlogPost::find(1);
echo $post->title; // "Mein erster Blog-Beitrag"

// Get specific locale
echo $post->getTranslation('title', 'en'); // "My First Blog Post"

// Get all translations
$allTitles = $post->getTranslations('title');
// [
//     'en' => 'My First Blog Post',
//     'de' => 'Mein erster Blog-Beitrag'
// ]

// Check if translation exists
if ($post->hasTranslation('title', 'fr')) {
    // ...
}
```

### Fallback Locales

```php
// In config/app.php
'fallback_locale' => 'en',

// If German translation missing, falls back to English
app()->setLocale('de');
$post = BlogPost::find(1);
echo $post->title; // Falls back to English if German not set
```

### Using with Inertia

Pass translated content to Vue components:

```php
use App\Models\BlogPost;
use Inertia\Inertia;

Route::get('/blog/{post}', function (BlogPost $post) {
    return Inertia::render('Blog/Show', [
        'post' => [
            'id' => $post->id,
            'title' => $post->title, // Automatically uses current locale
            'content' => $post->content,
            'excerpt' => $post->excerpt,
            // Or pass all translations
            'titleTranslations' => $post->getTranslations('title'),
        ]
    ]);
});
```

---

## Migration Example

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();

            // JSON columns for translatable fields
            $table->json('title');
            $table->json('content');
            $table->json('excerpt')->nullable();

            // Non-translatable fields
            $table->string('slug')->unique();
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
```

---

## Configuration

### Supported Locales

Edit `config/app.php`:

```php
// Available locales
'supported_locales' => ['en', 'de'],

// Default locale
'locale' => 'en',

// Fallback locale
'fallback_locale' => 'en',
```

### Locale Detection Middleware

Create middleware to detect and set locale from session:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Get locale from session or use default
        $locale = session('locale', config('app.locale'));

        // Validate against supported locales
        $supportedLocales = config('app.supported_locales', ['en', 'de']);
        if (in_array($locale, $supportedLocales, true)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
```

Register in `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->web(append: [
        \App\Http\Middleware\SetLocale::class,
        \App\Http\Middleware\HandleInertiaRequests::class,
    ]);
})
```

---

## Best Practices

### Frontend (vue-i18n)

- ✅ UI elements (buttons, labels, navigation)
- ✅ Form validation messages
- ✅ Static page content
- ✅ Error messages
- ✅ Tooltips and help text

### Backend (Laravel Translatable)

- ✅ Blog posts, articles, pages
- ✅ Product descriptions
- ✅ User-generated content
- ✅ Category names
- ✅ Any database content that needs translation

### Example: Blog System

**Frontend (vue-i18n):**
```vue
<template>
    <div>
        <!-- UI elements -->
        <h1>{{ $t('navigation.blog') }}</h1>
        <button>{{ $t('actions.create') }}</button>

        <!-- Backend content -->
        <article v-for="post in posts">
            <h2>{{ post.title }}</h2>
            <p>{{ post.excerpt }}</p>
        </article>
    </div>
</template>
```

**Backend (Laravel):**
```php
// Controller
$posts = BlogPost::all()->map(fn($post) => [
    'id' => $post->id,
    'title' => $post->title, // Translatable
    'excerpt' => $post->excerpt, // Translatable
]);

return Inertia::render('Blog/Index', [
    'posts' => $posts
]);
```

---

## Testing Translations

```php
// Test in Tinker
php artisan tinker

// Set locale
app()->setLocale('de');

// Test model
$post = App\Models\BlogPost::first();
echo $post->title; // German title

app()->setLocale('en');
echo $post->title; // English title
```

---

## Resources

- [vue-i18n Documentation](https://vue-i18n.intlify.dev/)
- [Laravel Translatable Documentation](https://github.com/spatie/laravel-translatable)
- [Laravel Localization](https://laravel.com/docs/12.x/localization)
