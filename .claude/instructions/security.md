# Security Principles & Best Practices

## Input Validation

### NEVER Trust User Input

- ✅ **Always validate ALL inputs** using FormRequest classes
- ❌ **NEVER use inline validation** in controllers
- ✅ Include both validation rules and custom error messages

**Example:**

```php
// ✅ Good - FormRequest
php artisan make:request StoreUserRequest

// In StoreUserRequest.php
public function rules(): array
{
    return [
        'email' => ['required', 'email', 'unique:users'],
        'name' => ['required', 'string', 'max:255'],
    ];
}
```

### Check Existing Patterns

Before creating FormRequests, check sibling files:

- Are rules defined as arrays or strings?
- What validation patterns are used?

## Output Escaping

### Blade Templates

```blade
{{-- ✅ Good - Auto-escaped --}}
{{ $userInput }}

{{-- ❌ Dangerous - Unescaped HTML --}}
{!! $userInput !!}
```

**Only use `{!! !!}` for:**

- Trusted admin content
- Sanitized HTML (e.g., from TipTap editor with proper sanitization)

### Vue Components

```vue
<!-- ✅ Safe - Text interpolation -->
<div>{{ userInput }}</div>

<!-- ⚠️ Dangerous - Manual escaping required -->
<div v-html="sanitizedHtml"></div>
```

**Always sanitize before v-html:**

```javascript
import DOMPurify from 'dompurify';

const sanitizedHtml = DOMPurify.sanitize(userInput);
```

## Authentication & Authorization

### Laravel Sanctum (API)

- Use Sanctum for API authentication
- Token-based for SPAs
- Configure CORS properly in `config/cors.php`

### Laravel Fortify (Scaffolding)

- Use Fortify for auth scaffolding
- Don't bypass built-in features
- Customize views, not core logic

### Gates & Policies

```php
// ✅ Good - Use Gates/Policies
Gate::define('update-post', function (User $user, Post $post) {
    return $user->id === $post->user_id;
});

// In Controller
$this->authorize('update-post', $post);

// ❌ Bad - Manual checks
if ($user->id !== $post->user_id) {
    abort(403);
}
```

### Never Expose Sensitive Data

```php
// ✅ Good - Hide sensitive attributes
protected $hidden = [
    'password',
    'remember_token',
    'api_token',
];

// ✅ Good - Use API Resources
return new UserResource($user);
```

## CSRF Protection

- ✅ Auto-enabled for web routes
- ✅ Inertia.js handles CSRF tokens automatically
- ❌ Don't disable CSRF unless absolutely necessary (API routes only)

## Database Security

### Always Use Parameterized Queries

```php
// ✅ Good - Eloquent (parameterized by default)
User::where('email', $email)->first();

// ✅ Good - Query Builder (still parameterized)
DB::table('users')->where('email', $email)->first();

// ❌ NEVER - Raw SQL with user input
DB::select("SELECT * FROM users WHERE email = '$email'");

// ✅ Only if absolutely necessary - Bind parameters
DB::select('SELECT * FROM users WHERE email = ?', [$email]);
```

### N+1 Query Prevention

```php
// ❌ Bad - N+1 queries (security risk via performance)
$posts = Post::all();
foreach ($posts as $post) {
    echo $post->author->name; // N queries
}

// ✅ Good - Eager loading
$posts = Post::with('author')->get();
```

## File Uploads

### Validation

```php
'avatar' => ['required', 'image', 'max:2048', 'mimes:jpeg,png,jpg'],
'document' => ['required', 'file', 'max:10240', 'mimes:pdf,doc,docx'],
```

### Storage

```php
// ✅ Good - Store outside public directory
$path = $request->file('avatar')->store('avatars', 'private');

// ✅ Use signed URLs for access
return Storage::temporaryUrl($path, now()->addMinutes(5));

// ❌ Bad - Direct public access
$path = $request->file('avatar')->store('avatars', 'public');
```

## Content Security Policy (CSP)

**Use spatie/laravel-csp:**

```php
// config/csp.php
'default-src' => ['self'],
'script-src' => ['self', 'unsafe-inline'], // Only if absolutely necessary
'style-src' => ['self', 'unsafe-inline'],
```

## Activity Logging

**Use spatie/laravel-activitylog for auditing:**

```php
activity()
    ->performedOn($post)
    ->causedBy($user)
    ->log('Post updated');
```

## Environment Variables

```php
// ❌ NEVER - Direct env() access outside config files
$key = env('API_KEY');

// ✅ ALWAYS - Use config() helper
$key = config('services.api.key');
```

**Why:** After `php artisan config:cache`, `env()` returns `null`.

## Rate Limiting

```php
// routes/api.php
Route::middleware('throttle:60,1')->group(function () {
    // Max 60 requests per minute
});
```

## Regular Security Practices

1. **Keep dependencies updated:** `composer update`, `npm update`
2. **Run security audits:** `composer audit`, `npm audit`
3. **Review code for vulnerabilities** before merging
4. **Use HTTPS** in production (enforce in middleware)
5. **Backup database regularly**
6. **Monitor logs** for suspicious activity (`storage/logs/`)

## Protected Areas (Ask Before Modifying)

- Database Migrations (schema changes need review)
- Security Code (authentication, authorization, encryption)
- API Contracts (breaking changes affect consumers)
- Configuration files with secrets (.env, config files)
