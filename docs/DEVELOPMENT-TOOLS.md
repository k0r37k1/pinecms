# Development Tools Guide

PineCMS uses three essential Laravel packages for development, backups, and media management.

---

## Laravel Telescope (Development)

Telescope provides debugging and monitoring capabilities for your Laravel application.

### Access Telescope

```bash
# Start development server
php artisan serve

# Access Telescope Dashboard
http://localhost:8000/telescope
```

### What Telescope Monitors

- **Requests** - All HTTP requests with response time
- **Commands** - Artisan commands executed
- **Schedule** - Scheduled tasks
- **Jobs** - Queued jobs status
- **Exceptions** - All exceptions thrown
- **Logs** - Application logs
- **Dumps** - `dump()` and `dd()` calls
- **Queries** - All database queries with execution time
- **Models** - Eloquent model events
- **Events** - Fired events and listeners
- **Mail** - Sent emails
- **Notifications** - Sent notifications
- **Cache** - Cache hits/misses
- **Redis** - Redis commands
- **Views** - Rendered views
- **Gates** - Authorization checks

### Configuration

Edit `config/telescope.php`:

```php
// Only in local environment
Telescope::filter(function (IncomingEntry $entry) {
    if ($this->app->environment('local')) {
        return true;
    }

    return $entry->isReportableException() ||
           $entry->isFailedRequest() ||
           $entry->isFailedJob() ||
           $entry->isScheduledTask() ||
           $entry->hasMonitoredTag();
});
```

### Access Control

In `app/Providers/TelescopeServiceProvider.php`:

```php
protected function gate(): void
{
    Gate::define('viewTelescope', function ($user) {
        return in_array($user->email, [
            'admin@example.com',
        ]);
    });
}
```

### Disable in Production

Set in `.env`:

```env
TELESCOPE_ENABLED=false
```

---

## Laravel Backup (Spatie)

Automated backup solution for files and database.

### Create Backup

```bash
# Run backup manually
php artisan backup:run

# Only backup database
php artisan backup:run --only-db

# Only backup files
php artisan backup:run --only-files

# Disable notifications
php artisan backup:run --disable-notifications
```

### Schedule Automatic Backups

In `routes/console.php` or `app/Console/Kernel.php`:

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('backup:run')->daily()->at('01:00');
```

### Monitor Backups

```bash
# Check backup health
php artisan backup:monitor

# List all backups
php artisan backup:list
```

### Cleanup Old Backups

```bash
# Cleanup based on retention policy
php artisan backup:clean
```

### Configuration

Edit `config/backup.php`:

**What to Backup:**
```php
'source' => [
    'files' => [
        'include' => [
            base_path(),
        ],
        'exclude' => [
            base_path('vendor'),
            base_path('node_modules'),
            base_path('storage/framework'),
        ],
    ],
    'databases' => [
        env('DB_CONNECTION', 'sqlite'),
    ],
],
```

**Where to Store:**
```php
'destination' => [
    'disks' => [
        'backups',  // storage/app/backups
        // 's3',    // AWS S3
    ],
],
```

**Retention Policy:**
```php
'cleanup' => [
    'default_strategy' => [
        'keep_all_backups_for_days' => 7,
        'keep_daily_backups_for_days' => 16,
        'keep_weekly_backups_for_weeks' => 8,
        'keep_monthly_backups_for_months' => 4,
        'keep_yearly_backups_for_years' => 2,
        'delete_oldest_backups_when_using_more_megabytes_than' => 5000,
    ],
],
```

### Email Notifications

Set in `.env`:

```env
BACKUP_MAIL_TO=your@example.com
MAIL_FROM_ADDRESS=hello@example.com
MAIL_FROM_NAME="PineCMS"
```

### Slack Notifications

```env
BACKUP_SLACK_WEBHOOK_URL=https://hooks.slack.com/services/YOUR/WEBHOOK/URL
```

### Storage Configuration

In `config/filesystems.php`, the `backups` disk is already configured:

```php
'backups' => [
    'driver' => 'local',
    'root' => storage_path('app/backups'),
],
```

---

## Laravel Medialibrary (Spatie)

Associate files with Eloquent models.

### Basic Usage

**Add trait to Model:**

```php
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BlogPost extends Model implements HasMedia
{
    use InteractsWithMedia;
}
```

**Upload Files:**

```php
// From request
$post->addMedia($request->file('image'))
    ->toMediaCollection('images');

// From URL
$post->addMediaFromUrl('https://example.com/image.jpg')
    ->toMediaCollection('images');

// From disk
$post->addMedia(storage_path('temp/image.jpg'))
    ->toMediaCollection('images');
```

**Retrieve Files:**

```php
// Get all media
$mediaItems = $post->getMedia('images');

// Get first media
$media = $post->getFirstMedia('images');

// Get URL
$url = $post->getFirstMediaUrl('images');

// Get path
$path = $post->getFirstMediaPath('images');
```

**Delete Files:**

```php
// Delete specific media
$media = $post->getFirstMedia('images');
$media->delete();

// Clear entire collection
$post->clearMediaCollection('images');
```

### Image Conversions

Define conversions in your model:

```php
use Spatie\MediaLibrary\MediaCollections\Models\Media;

public function registerMediaConversions(?Media $media = null): void
{
    $this->addMediaConversion('thumb')
        ->width(368)
        ->height(232)
        ->sharpen(10);

    $this->addMediaConversion('preview')
        ->width(800)
        ->height(600)
        ->performOnCollections('images');
}
```

**Get conversion URL:**

```php
$url = $post->getFirstMediaUrl('images', 'thumb');
```

### Multiple Collections

```php
// Featured image
$post->addMedia($request->file('featured'))
    ->toMediaCollection('featured');

// Gallery images
foreach ($request->file('gallery') as $image) {
    $post->addMedia($image)
        ->toMediaCollection('gallery');
}
```

### Custom Properties

```php
$post->addMedia($request->file('image'))
    ->withCustomProperties(['alt' => 'Alt text', 'caption' => 'Caption'])
    ->toMediaCollection('images');

// Retrieve
$media = $post->getFirstMedia('images');
$alt = $media->getCustomProperty('alt');
```

### Responsive Images

```php
public function registerMediaConversions(?Media $media = null): void
{
    $this->addMediaConversion('responsive')
        ->withResponsiveImages()
        ->width(1200);
}
```

**Render responsive image:**

```blade
{!! $post->getFirstMedia('images')->img('responsive') !!}
```

### Usage with Inertia

```php
use App\Models\BlogPost;
use Inertia\Inertia;

Route::get('/blog/{post}', function (BlogPost $post) {
    return Inertia::render('Blog/Show', [
        'post' => [
            'id' => $post->id,
            'title' => $post->title,
            'featured_image' => $post->getFirstMediaUrl('featured'),
            'thumbnail' => $post->getFirstMediaUrl('featured', 'thumb'),
            'gallery' => $post->getMedia('gallery')->map(fn($media) => [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'thumb' => $media->getUrl('thumb'),
            ]),
        ]
    ]);
});
```

### Configuration

Edit `config/media-library.php`:

**Storage Disk:**
```php
'disk_name' => env('MEDIA_DISK', 'public'),
```

**File Size Limit:**
```php
'max_file_size' => 1024 * 1024 * 10, // 10MB
```

**Queue Conversions:**
```php
'queue_conversions_by_default' => true,
```

**Image Driver:**
```php
'image_driver' => env('IMAGE_DRIVER', 'gd'),
```

### Storage Setup

Link public storage:

```bash
php artisan storage:link
```

This creates `public/storage` → `storage/app/public` symlink.

---

## Environment Variables

Add to `.env`:

```env
# Telescope (Development Only)
TELESCOPE_ENABLED=true

# Backup
BACKUP_MAIL_TO=your@example.com
BACKUP_SLACK_WEBHOOK_URL=
BACKUP_ARCHIVE_PASSWORD=

# Media Library
MEDIA_DISK=public
IMAGE_DRIVER=gd
MEDIA_QUEUE=
QUEUE_CONVERSIONS_BY_DEFAULT=true
```

---

## Scheduler Setup (Production)

Add to crontab:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

This will run:
- Automatic backups (daily at 01:00)
- Backup monitoring
- Backup cleanup

---

## Image Optimization (Optional)

For better image optimization, install these tools:

**Ubuntu/Debian:**
```bash
sudo apt install jpegoptim optipng pngquant gifsicle libavif-bin
npm install -g svgo
```

**macOS:**
```bash
brew install jpegoptim optipng pngquant svgo gifsicle libavif
```

---

## Artisan Commands Reference

### Telescope
```bash
php artisan telescope:clear      # Clear all entries
php artisan telescope:prune      # Prune old entries
php artisan telescope:pause      # Pause recording
php artisan telescope:resume     # Resume recording
```

### Backup
```bash
php artisan backup:run           # Create backup
php artisan backup:clean         # Cleanup old backups
php artisan backup:list          # List all backups
php artisan backup:monitor       # Check backup health
```

### Storage
```bash
php artisan storage:link         # Create storage symlink
```

---

## Troubleshooting

### Telescope Not Loading

1. Check if enabled in `.env`: `TELESCOPE_ENABLED=true`
2. Clear config cache: `php artisan config:clear`
3. Check access permissions in `TelescopeServiceProvider`

### Backup Failing

1. Check disk permissions: `chmod -R 775 storage/app/backups`
2. Check available disk space
3. Verify database connection
4. Check excluded paths in `config/backup.php`

### Media Upload Failing

1. Check storage permissions: `chmod -R 775 storage/app/public`
2. Run: `php artisan storage:link`
3. Check `max_file_size` in `config/media-library.php`
4. Verify `upload_max_filesize` in `php.ini`

### Image Conversions Not Working

1. Check image driver: `php -m | grep -i gd` or `php -m | grep -i imagick`
2. Install image optimization tools (see above)
3. Check queue is running: `php artisan queue:work`
4. Check conversion definitions in model

---

## Resources

- [Laravel Telescope Docs](https://laravel.com/docs/12.x/telescope)
- [Laravel Backup Docs](https://spatie.be/docs/laravel-backup)
- [Laravel Medialibrary Docs](https://spatie.be/docs/laravel-medialibrary)
