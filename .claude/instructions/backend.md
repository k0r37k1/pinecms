# Backend Development Instructions

## Laravel 12 Backend Best Practices

### File Creation with Artisan

**ALWAYS use `php artisan make:` commands:**

```bash
# List all available commands first
# Use MCP tool: list-artisan-commands

# Models
php artisan make:model Post -mfs
# -m: migration, -f: factory, -s: seeder

# Controllers
php artisan make:controller PostController
php artisan make:controller Api/PostController --api
php artisan make:controller PostController --resource

# Services (generic class)
php artisan make:class Services/PostService

# Form Requests
php artisan make:request StorePostRequest
php artisan make:request UpdatePostRequest

# Events & Listeners
php artisan make:event PostPublished
php artisan make:listener SendPostNotification

# Jobs
php artisan make:job ProcessPost
php artisan make:job ProcessPost --sync  # Synchronous job

# Middleware (in Laravel 12, register in bootstrap/app.php)
php artisan make:middleware EnsureTenantAccess

# Policies
php artisan make:policy PostPolicy --model=Post

# Resources (API)
php artisan make:resource PostResource
php artisan make:resource PostCollection
```

**IMPORTANT:** Always pass `--no-interaction` flag for AI execution:

```bash
php artisan make:model Post -mfs --no-interaction
```

## Controllers

### Controller Best Practices

**Keep controllers thin:**

```php
// ❌ Bad - Fat controller with business logic
class PostController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
        ]);

        $post = Post::create($request->all());

        Cache::tags(['posts'])->flush();

        Mail::to($post->author)->send(new PostCreated($post));

        Log::info('Post created', ['post_id' => $post->id]);

        return redirect()->route('posts.show', $post);
    }
}

// ✅ Good - Thin controller, delegate to Service
class PostController extends Controller
{
    public function __construct(
        private readonly PostService $postService
    ) {}

    public function store(StorePostRequest $request): RedirectResponse
    {
        $post = $this->postService->create($request->validated());

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Post created successfully');
    }
}
```

### Single Action Controllers

For simple actions, consider invokable controllers:

```php
class PublishPostController extends Controller
{
    public function __invoke(Post $post): RedirectResponse
    {
        $this->authorize('publish', $post);

        $post->publish();

        return back()->with('success', 'Post published');
    }
}

// routes/web.php
Route::post('/posts/{post}/publish', PublishPostController::class);
```

## Services (Business Logic Layer)

### Service Pattern

```php
// app/Services/PostService.php
declare(strict_types=1);

namespace App\Services;

use App\Models\Post;
use App\Events\PostCreated;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PostService
{
    /**
     * Create a new post
     */
    public function create(array $data): Post
    {
        return DB::transaction(function () use ($data) {
            $post = Post::create($data);

            event(new PostCreated($post));

            Cache::tags(['posts'])->flush();

            return $post;
        });
    }

    /**
     * Update an existing post
     */
    public function update(Post $post, array $data): Post
    {
        return DB::transaction(function () use ($post, $data) {
            $post->update($data);

            Cache::tags(['posts'])->flush();

            return $post->fresh();
        });
    }

    /**
     * Delete a post
     */
    public function delete(Post $post): bool
    {
        return DB::transaction(function () use ($post) {
            $deleted = $post->delete();

            Cache::tags(['posts'])->flush();

            return $deleted;
        });
    }
}
```

### Service Registration

**Register in AppServiceProvider:**

```php
// app/Providers/AppServiceProvider.php
public function register(): void
{
    $this->app->singleton(PostService::class);
}
```

## Models & Eloquent

### Model Best Practices

```php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'published_at',
        'author_id',
    ];

    /**
     * Attribute casting (Laravel 11+ method)
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
            'metadata' => 'array',
        ];
    }

    /**
     * Relationships with explicit return types
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Query Scopes
     */
    public function scopePublished(Builder $query): void
    {
        $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true);
    }

    /**
     * Accessors (Laravel 11+ attribute method)
     */
    protected function excerpt(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::limit($this->content, 200),
        );
    }

    /**
     * Mutators (Laravel 11+ attribute method)
     */
    protected function title(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Str::title($value),
        );
    }
}
```

### Query Scopes Usage

```php
// ✅ Good - Readable, reusable
$posts = Post::published()
    ->featured()
    ->with('author')
    ->latest()
    ->paginate(15);

// ❌ Bad - Inline conditions everywhere
$posts = Post::whereNotNull('published_at')
    ->where('published_at', '<=', now())
    ->where('is_featured', true)
    ->latest()
    ->paginate(15);
```

## Validation with FormRequests

### FormRequest Best Practices

```php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Post::class);
    }

    /**
     * Get the validation rules
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('posts', 'slug'),
            ],
            'content' => ['required', 'string'],
            'published_at' => ['nullable', 'date', 'after_or_equal:today'],
            'is_featured' => ['boolean'],
        ];
    }

    /**
     * Custom error messages
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Please provide a title for your post.',
            'title.max' => 'The title cannot exceed 255 characters.',
            'slug.unique' => 'This slug is already in use.',
            'published_at.after_or_equal' => 'Published date cannot be in the past.',
        ];
    }

    /**
     * Custom attribute names
     */
    public function attributes(): array
    {
        return [
            'published_at' => 'publication date',
        ];
    }

    /**
     * Prepare data for validation
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->title),
        ]);
    }

    /**
     * Get validated data with additional processing
     */
    public function validated($key = null, $default = null): array
    {
        return array_merge(parent::validated(), [
            'author_id' => $this->user()->id,
        ]);
    }
}
```

**Check sibling FormRequests for array vs string validation rules:**

```php
// Some projects use array syntax
'title' => ['required', 'string', 'max:255']

// Others use string syntax
'title' => 'required|string|max:255'

// Follow existing project convention!
```

## Database Queries & Optimization

### N+1 Query Prevention

**Use MCP tool `database-query` to check actual queries:**

```php
// ❌ Bad - N+1 queries
$posts = Post::all(); // 1 query
foreach ($posts as $post) {
    echo $post->author->name; // N queries
    foreach ($post->comments as $comment) { // N queries
        echo $comment->user->name; // N * M queries
    }
}

// ✅ Good - Eager loading
$posts = Post::with([
    'author',
    'comments.user'
])->get(); // 3 queries total
```

### Conditional Eager Loading

```php
$posts = Post::query()
    ->when($includeComments, fn ($q) => $q->with('comments'))
    ->when($includeAuthor, fn ($q) => $q->with('author'))
    ->get();
```

### Laravel 12 - Limit Eager Loaded Records

```php
$posts = Post::with([
    'comments' => fn ($query) => $query->latest()->limit(5)
])->get();
```

### Query Builder vs Eloquent

```php
// ✅ Prefer Eloquent for simple queries
$posts = Post::where('published', true)->get();

// ✅ Use Query Builder for complex queries
$stats = DB::table('posts')
    ->select('author_id', DB::raw('COUNT(*) as post_count'))
    ->groupBy('author_id')
    ->having('post_count', '>', 10)
    ->get();

// ❌ Never use raw SQL with user input (SQL injection risk!)
// ❌ DB::select("SELECT * FROM posts WHERE id = $id");

// ✅ If you must use raw SQL, bind parameters
DB::select('SELECT * FROM posts WHERE id = ?', [$id]);
```

## Events & Listeners

### Event-Driven Architecture

**NEVER use Hooks - ALWAYS use Events:**

```php
// app/Events/PostPublished.php
declare(strict_types=1);

namespace App\Events;

use App\Models\Post;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostPublished
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Post $post
    ) {}
}

// app/Listeners/SendPostNotification.php
declare(strict_types=1);

namespace App\Listeners;

use App\Events\PostPublished;
use App\Notifications\NewPostNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPostNotification implements ShouldQueue
{
    public function handle(PostPublished $event): void
    {
        $event->post->author->notify(
            new NewPostNotification($event->post)
        );
    }
}

// app/Providers/EventServiceProvider.php
protected $listen = [
    PostPublished::class => [
        SendPostNotification::class,
        ClearPostCache::class,
        LogPostActivity::class,
    ],
];
```

### Dispatching Events

```php
// From Service
public function publish(Post $post): Post
{
    $post->update(['published_at' => now()]);

    event(new PostPublished($post));

    return $post;
}

// Or use Event facade
Event::dispatch(new PostPublished($post));

// Or use model method
$post->fireModelEvent('published');
```

## Queues & Jobs

### Job Best Practices

```php
declare(strict_types=1);

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Maximum number of attempts
     */
    public int $tries = 3;

    /**
     * Timeout in seconds
     */
    public int $timeout = 120;

    /**
     * Create a new job instance
     */
    public function __construct(
        public readonly Post $post
    ) {}

    /**
     * Execute the job
     */
    public function handle(): void
    {
        // Process the post
        $this->post->generateThumbnail();
        $this->post->extractKeywords();
        $this->post->updateSearchIndex();
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Post processing failed', [
            'post_id' => $this->post->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
```

### Dispatching Jobs

```php
// Dispatch immediately
ProcessPost::dispatch($post);

// Dispatch after delay
ProcessPost::dispatch($post)->delay(now()->addMinutes(5));

// Dispatch to specific queue
ProcessPost::dispatch($post)->onQueue('processing');

// Dispatch conditionally
ProcessPost::dispatchIf($condition, $post);

// Dispatch synchronously (for testing)
ProcessPost::dispatchSync($post);
```

## Caching Strategies

### Cache Best Practices

```php
use Illuminate\Support\Facades\Cache;

// ✅ Good - Use tags for related cache
Cache::tags(['posts', 'featured'])->put('featured-posts', $posts, now()->addHour());

// Retrieve
$posts = Cache::tags(['posts', 'featured'])->get('featured-posts');

// Clear related cache
Cache::tags(['posts'])->flush();

// ✅ Good - Use remember for auto-caching
$posts = Cache::remember('featured-posts', now()->addHour(), function () {
    return Post::featured()->with('author')->get();
});

// ✅ Good - Cache with model binding
$post = Cache::remember("post:{$id}", now()->addDay(), function () use ($id) {
    return Post::with('author', 'comments')->findOrFail($id);
});
```

### Cache Invalidation

```php
// In Service
public function update(Post $post, array $data): Post
{
    $post->update($data);

    // Invalidate related caches
    Cache::tags(['posts'])->flush();
    Cache::forget("post:{$post->id}");

    return $post->fresh();
}
```

## API Development

### API Resources

```php
declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'published_at' => $this->published_at?->toIso8601String(),

            // Conditional attributes
            'content' => $this->when($request->user()?->can('view', $this->resource), $this->content),

            // Relationships
            'author' => new UserResource($this->whenLoaded('author')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),

            // Computed attributes
            'is_published' => $this->isPublished(),

            // Metadata
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
```

### API Controller

```php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('author')
            ->published()
            ->paginate(15);

        return PostResource::collection($posts);
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);

        return new PostResource($post->load('author', 'comments'));
    }
}
```

### API Versioning

```php
// routes/api.php
Route::prefix('v1')->group(function () {
    Route::apiResource('posts', Api\V1\PostController::class);
});

Route::prefix('v2')->group(function () {
    Route::apiResource('posts', Api\V2\PostController::class);
});
```

## Database Transactions

### When to Use Transactions

```php
use Illuminate\Support\Facades\DB;

// ✅ Use transactions for multiple related operations
public function transferOwnership(Post $post, User $newOwner): Post
{
    return DB::transaction(function () use ($post, $newOwner) {
        $oldOwner = $post->author;

        $post->update(['author_id' => $newOwner->id]);

        $oldOwner->decrement('post_count');
        $newOwner->increment('post_count');

        event(new PostOwnershipTransferred($post, $oldOwner, $newOwner));

        return $post->fresh();
    });
}

// ✅ Manual transaction control
DB::beginTransaction();

try {
    // Operations
    $post = Post::create($data);
    $post->author->increment('post_count');

    DB::commit();
} catch (\Throwable $e) {
    DB::rollBack();
    throw $e;
}
```

## Middleware (Laravel 12)

### Creating Middleware

```bash
php artisan make:middleware EnsureTenantAccess
```

```php
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTenantAccess
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (! $request->user()->hasAccessToTenant($request->route('tenant'))) {
            abort(403, 'Unauthorized tenant access');
        }

        return $next($request);
    }
}
```

### Registering Middleware (Laravel 12)

```php
// bootstrap/app.php
return Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
        ]);

        $middleware->alias([
            'tenant' => \App\Http\Middleware\EnsureTenantAccess::class,
        ]);
    })
    ->create();
```

## Authorization (Gates & Policies)

### Policy Best Practices

```php
declare(strict_types=1);

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine if the user can view the post
     */
    public function view(User $user, Post $post): bool
    {
        return $post->isPublished() || $user->id === $post->author_id;
    }

    /**
     * Determine if the user can update the post
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->author_id;
    }

    /**
     * Determine if the user can delete the post
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->author_id && ! $post->isPublished();
    }
}
```

### Using Policies

```php
// In Controller
public function update(UpdatePostRequest $request, Post $post)
{
    $this->authorize('update', $post);

    // ...
}

// In Blade
@can('update', $post)
    <a href="{{ route('posts.edit', $post) }}">Edit</a>
@endcan

// In Code
if ($user->can('update', $post)) {
    // ...
}
```

## Common Pitfalls

### Mistake #1: Using env() Outside Config

```php
// ❌ Bad - Returns null after config:cache
$apiKey = env('API_KEY');

// ✅ Good - Always use config()
$apiKey = config('services.api.key');
```

### Mistake #2: Not Using Eager Loading

```php
// ❌ Bad - N+1 queries
Post::all()->each(fn ($post) => $post->author->name);

// ✅ Good - Eager loading
Post::with('author')->get()->each(fn ($post) => $post->author->name);
```

### Mistake #3: Fat Controllers

```php
// ❌ Bad - Controller with business logic
public function store(Request $request) {
    // 50 lines of business logic
}

// ✅ Good - Delegate to Service
public function store(StorePostRequest $request) {
    return $this->postService->create($request->validated());
}
```

### Mistake #4: Not Using Transactions

```php
// ❌ Bad - Partial data on failure
$post = Post::create($data);
$post->author->increment('post_count'); // Fails, but post was created

// ✅ Good - All or nothing
DB::transaction(function () use ($data) {
    $post = Post::create($data);
    $post->author->increment('post_count');
    return $post;
});
```

### Mistake #5: Missing Type Declarations

```php
// ❌ Bad - No declare strict_types
namespace App\Services;

// ✅ Good - Always use strict types
declare(strict_types=1);

namespace App\Services;
```

## Performance Best Practices

1. **Use Eager Loading** - Prevent N+1 queries
2. **Cache Expensive Queries** - Use Cache::remember()
3. **Index Database Columns** - Add indexes to frequently queried columns
4. **Use Queues** - Offload time-consuming tasks
5. **Optimize Database Queries** - Use database-query MCP tool to monitor
6. **Use Chunking** - Process large datasets in chunks
7. **Lazy Collections** - For memory-efficient iterations

```php
// Chunking
Post::chunk(100, function ($posts) {
    foreach ($posts as $post) {
        // Process each post
    }
});

// Lazy Collections
Post::cursor()->each(function ($post) {
    // Process with minimal memory
});
```
