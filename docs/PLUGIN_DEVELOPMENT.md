# 🛠️ PineCMS Plugin Development Guide

> **Version:** 1.0 (Draft)
> **Plugin API:** v1.1.0+
> **Last Updated:** 2025-01-26

---

## 🎯 Overview

PineCMS uses an **Event-Driven Plugin Architecture** based on Laravel's Event System. Plugins can hook into core events to extend functionality without modifying core code.

**Key Principles:**

- Event-Driven (Laravel Events)
- Service Provider Pattern (Laravel)
- Vue/Inertia.js for Admin UI
- Blade Templates for Frontend
- Database Migrations Support
- Asset Compilation (Vite)

---

## 📁 Plugin Structure

```
plugins/
  my-plugin/
    plugin.json                 # Plugin metadata
    PluginServiceProvider.php   # Service provider (entry point)
    routes/
      web.php                   # Frontend routes
      admin.php                 # Admin routes (Inertia.js)
    src/
      Events/                   # Custom events
      Listeners/                # Event listeners
      Models/                   # Eloquent models
      Services/                 # Business logic
    database/
      migrations/               # Database migrations
    resources/
      views/                    # Blade templates
      js/                       # Vue/JS files
      css/                      # Stylesheets
    public/                     # Compiled assets
    lang/                       # Translations
    README.md                   # Plugin documentation
```

---

## 📝 plugin.json

```json
{
  "name": "my-plugin",
  "displayName": "My Plugin",
  "description": "Short description of what this plugin does",
  "version": "1.0.0",
  "author": "Your Name",
  "email": "your@email.com",
  "license": "MIT",
  "requires": {
    "pinecms": ">=1.1.0"
  },
  "homepage": "https://github.com/yourname/pinecms-my-plugin",
  "keywords": ["newsletter", "email", "marketing"]
}
```

---

## 🔌 PluginServiceProvider.php

```php
<?php

declare(strict_types=1);

namespace Plugins\MyPlugin;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\PostPublished;
use Plugins\MyPlugin\Listeners\SendNewsletterOnPublish;

class PluginServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register plugin services
        $this->app->singleton(MyPluginService::class);
    }

    public function boot(): void
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/admin.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'my-plugin');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // Load translations
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'my-plugin');

        // Register event listeners
        Event::listen(
            PostPublished::class,
            SendNewsletterOnPublish::class
        );

        // Publish assets
        $this->publishes([
            __DIR__ . '/public' => public_path('plugins/my-plugin'),
        ], 'my-plugin-assets');

        // Publish config
        $this->publishes([
            __DIR__ . '/config/my-plugin.php' => config_path('my-plugin.php'),
        ], 'my-plugin-config');
    }
}
```

---

## 🎯 Available Core Events

Plugins can listen to these core events:

```php
// Content Events
App\Events\PostCreated          // When post is created
App\Events\PostUpdated          // When post is updated
App\Events\PostPublished        // When post is published
App\Events\PostDeleted          // When post is deleted

App\Events\PageCreated
App\Events\PageUpdated
App\Events\PagePublished
App\Events\PageDeleted

// Comment Events
App\Events\CommentCreated
App\Events\CommentApproved
App\Events\CommentRejected

// User Events
App\Events\UserRegistered
App\Events\UserLoggedIn
App\Events\UserLoggedOut
App\Events\UserUpdated

// Media Events
App\Events\MediaUploaded
App\Events\MediaDeleted

// System Events
App\Events\ThemeActivated
App\Events\PluginActivated
App\Events\PluginDeactivated
App\Events\CacheCleared
```

---

## 🎨 Frontend Integration (Blade)

```blade
{{-- resources/views/widget.blade.php --}}
<div class="my-plugin-widget">
    <h3>{{ trans('my-plugin::messages.title') }}</h3>
    <p>{{ $content }}</p>
</div>
```

---

## 🖥️ Admin Integration (Inertia.js + Vue)

```php
// routes/admin.php
use Inertia\Inertia;

Route::middleware(['auth', 'admin'])->prefix('admin/my-plugin')->group(function () {
    Route::get('/settings', function () {
        return Inertia::render('MyPlugin/Settings', [
            'settings' => config('my-plugin'),
        ]);
    })->name('my-plugin.settings');
});
```

```vue
<!-- resources/js/Pages/MyPlugin/Settings.vue -->
<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineProps({
  settings: Object
})

const form = useForm({
  api_key: settings.api_key,
  enabled: settings.enabled
})

const submit = () => {
  form.post(route('my-plugin.settings.update'))
}
</script>

<template>
  <AdminLayout title="My Plugin Settings">
    <form @submit.prevent="submit">
      <!-- Form fields -->
    </form>
  </AdminLayout>
</template>
```

---

## 🗄️ Database Migrations

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('my_plugin_table', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('my_plugin_table');
    }
};
```

---

## ⚙️ Plugin Settings

Plugins can register settings that appear in Admin Panel → Plugins → Plugin Settings.

```php
// config/my-plugin.php
return [
    'enabled' => true,
    'api_key' => env('MY_PLUGIN_API_KEY'),
    'webhook_url' => env('MY_PLUGIN_WEBHOOK_URL'),
];
```

---

## 📦 Distribution

### 1. Create Release ZIP

```bash
# Remove development files
rm -rf node_modules vendor

# Install production dependencies
composer install --no-dev --optimize-autoloader
npm install --production
npm run build

# Create ZIP
cd plugins/
zip -r my-plugin-v1.0.0.zip my-plugin/
```

### 2. GitHub Release

- Tag version: `v1.0.0`
- Attach ZIP file
- Write release notes

### 3. Submit to Plugin Directory (Future)

- [plugins.pinecms.org](https://plugins.pinecms.org) (coming in v2.0)

---

## 🧪 Testing

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Plugins\MyPlugin\MyPluginService;

class MyPluginTest extends TestCase
{
    public function test_plugin_service_works(): void
    {
        $service = app(MyPluginService::class);

        $result = $service->doSomething();

        $this->assertTrue($result);
    }
}
```

---

## 📚 Resources

- **Core Events:** See `app/Events/` for all available events
- **Service Provider:** [Laravel Docs - Service Providers](https://laravel.com/docs/providers)
- **Inertia.js:** [inertiajs.com](https://inertiajs.com)
- **Vue 3:** [vuejs.org](https://vuejs.org)
- **PrimeVue:** [primevue.org](https://primevue.org)

---

## 💡 Examples

Check out the **Official Plugins** source code:

1. [Newsletter Plugin](https://github.com/pinecms/plugin-newsletter)
2. [Webhooks Plugin](https://github.com/pinecms/plugin-webhooks)
3. [Custom Fields Pro](https://github.com/pinecms/plugin-custom-fields-pro)
4. [Multi-Language](https://github.com/pinecms/plugin-multilanguage)
5. [Workflow](https://github.com/pinecms/plugin-workflow)
6. [Two-Factor Auth](https://github.com/pinecms/plugin-2fa)
7. [Form Builder](https://github.com/pinecms/plugin-form-builder)
8. [SEO Pro](https://github.com/pinecms/plugin-seo-pro)

---

## 🤝 Contributing

Want to contribute to official plugins or create your own?

- 💬 Join [Discussions](https://github.com/pinecms/pinecms/discussions)
- 🐛 Report issues on GitHub
- 📧 Email: <plugins@pinecms.org>

---

**Last Updated:** 2025-01-26
**Maintained By:** PineCMS Team
**License:** MIT
