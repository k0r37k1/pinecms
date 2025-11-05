# Architecture & Design Patterns

## Event-Driven Architecture (NEVER Hooks)

```
User Action → Controller → Service → Repository → Database
                            ↓
                          Event
                            ↓
                        Listeners (logging, notifications, caching, etc.)
```

### Event System

**Events Location:** `app/Events/`
**Listeners Location:** `app/Listeners/`
**Registration:** `app/Providers/EventServiceProvider.php`

**Example:**

```php
// Dispatch event from Service
event(new PostPublished($post));

// Listener handles side effects
class SendPostNotification
{
    public function handle(PostPublished $event): void
    {
        // Send notifications
    }
}
```

**❌ NEVER use Hooks** - This project uses Laravel's Event system exclusively.

## Layer Architecture

```
Controllers (HTTP Layer)
    ↓
Services (Business Logic Layer)
    ↓
Models (Eloquent ORM with Query Scopes)

DTOs (Data Transfer Objects)
    ↔ Between layers for data transfer

Events (Side Effects)
    ↔ Triggered by Services for cross-cutting concerns
```

### Separation of Concerns

- **Controllers:** HTTP handling, validation (via FormRequests), response formatting
- **Services:** Business logic, orchestration, event dispatching
- **Repositories/Models:** Data access, database queries
- **Views/Components:** Presentation layer
- **Event Listeners:** Side effects (logging, notifications, caching)

### Example Flow

```php
// Controller (HTTP Layer)
public function store(StorePostRequest $request): RedirectResponse
{
    $post = $this->postService->create($request->validated());

    return redirect()->route('posts.show', $post)
        ->with('success', 'Post created successfully');
}

// Service (Business Logic)
public function create(array $data): Post
{
    $post = Post::create($data);

    event(new PostCreated($post));

    return $post;
}

// Listener (Side Effects)
public function handle(PostCreated $event): void
{
    Cache::tags(['posts'])->flush();
    Log::info('Post created', ['post_id' => $event->post->id]);
}
```

## Flat-File Hybrid Storage Architecture

### Storage Strategy

- **SQLite:** Relational data (users, metadata, configurations, relationships)
- **Flat-File:** Content storage (pages, posts, documentation in markdown format)

### Benefits

- ✅ Simple backups (copy markdown files)
- ✅ Version control friendly (git diff on markdown)
- ✅ No complex database migrations for content
- ✅ Content can be edited directly in any text editor

### Implementation

**Use `filesystem` MCP for all flat-file operations:**

```php
// Read markdown content
$content = Storage::disk('content')->get('posts/my-post.md');

// Write markdown content
Storage::disk('content')->put('posts/my-post.md', $markdown);

// List content files
$files = Storage::disk('content')->files('posts');
```

**Database for metadata:**

```php
// Store metadata in SQLite
Post::create([
    'slug' => 'my-post',
    'title' => 'My Post',
    'file_path' => 'posts/my-post.md',
    'published_at' => now(),
]);
```

## N+1 Query Prevention

### The Problem

```php
// ❌ Bad - N+1 queries
$posts = Post::all(); // 1 query
foreach ($posts as $post) {
    echo $post->author->name; // N queries (one per post)
}
// Total: 1 + N queries
```

### The Solution

```php
// ✅ Good - Eager loading
$posts = Post::with('author')->get(); // 2 queries total
foreach ($posts as $post) {
    echo $post->author->name; // No additional queries
}
// Total: 2 queries
```

### Advanced Eager Loading

```php
// Multiple relationships
Post::with(['author', 'comments', 'tags'])->get();

// Nested relationships
Post::with('author.profile')->get();

// Conditional eager loading
Post::with(['comments' => function ($query) {
    $query->where('approved', true)->latest()->limit(5);
}])->get();

// Laravel 12 - Limit eager loaded records
Post::with(['comments' => function ($query) {
    $query->latest()->limit(10);
}])->get();
```

### Detection

Use `database-query` MCP tool to check actual queries:

```bash
# In tinker or test
DB::enableQueryLog();
// ... your code ...
dd(DB::getQueryLog());
```

## Laravel 12 Structure

### No Traditional Files

- ❌ No `app/Http/Middleware/` files
- ❌ No `app/Console/Kernel.php`
- ❌ No `app/Http/Kernel.php`

### New Structure

- ✅ `bootstrap/app.php` - Register middleware, exceptions, routing
- ✅ `bootstrap/providers.php` - Application service providers
- ✅ `routes/console.php` - Console routes
- ✅ `app/Console/Commands/` - Auto-registered commands

### Example: bootstrap/app.php

```php
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

## Database Best Practices

### Models & Relationships

```php
// ✅ Good - Explicit return types
public function author(): BelongsTo
{
    return $this->belongsTo(User::class);
}

// ✅ Good - Use casts() method (Laravel 11+)
protected function casts(): array
{
    return [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
    ];
}
```

### Query Scopes

```php
// Model
public function scopePublished(Builder $query): void
{
    $query->whereNotNull('published_at')
        ->where('published_at', '<=', now());
}

// Usage
Post::published()->latest()->get();
```

### Migrations

**When modifying columns, include ALL attributes:**

```php
// ❌ Bad - Other attributes will be lost
$table->string('name')->nullable()->change();

// ✅ Good - Include all previous attributes
$table->string('name', 255)->nullable()->index()->change();
```

## API Design (If Applicable)

### Use Eloquent API Resources

```php
// Resource
class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => new UserResource($this->whenLoaded('author')),
        ];
    }
}

// Controller
return PostResource::collection($posts);
```

### API Versioning

```php
// routes/api.php
Route::prefix('v1')->group(function () {
    Route::apiResource('posts', PostController::class);
});
```
