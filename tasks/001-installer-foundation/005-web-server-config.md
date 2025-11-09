---
task_id: 005
epic: 001-installer-foundation
title: Web Server Configuration Generator
status: pending
priority: high
estimated_effort: 4 hours
actual_effort: null
assignee: backend-architect
dependencies: [004]
tags: [installer, apache, nginx, week-2]
---

# Task 005: Web Server Configuration Generator

## 📋 Overview

Build web server configuration generators for Apache (.htaccess) and nginx (nginx.conf.example) to enable pretty URLs, security headers, and optimal performance settings for shared hosting environments.

**Why Important:** Most shared hosting uses Apache. Auto-generating .htaccess prevents "404 Not Found" errors and ensures security headers are applied. nginx configuration template helps VPS users configure their server.

## 🎯 Acceptance Criteria

- [ ] Apache .htaccess generator with mod_rewrite rules
- [ ] Security headers (X-Frame-Options, X-Content-Type-Options, Referrer-Policy)
- [ ] HTTPS redirect (optional, configurable)
- [ ] Disable directory listing
- [ ] Protect sensitive files (.env, .git, database/)
- [ ] nginx.conf.example template for manual configuration
- [ ] API endpoint for .htaccess generation
- [ ] Comprehensive unit tests
- [ ] PHPStan Level 8 compliance

## 🏗️ Implementation Steps

### Step 1: Create Web Server Config Generator Service

**File**: `app/Services/Installer/WebServerConfigGenerator.php`

**Description**: Service to generate Apache and nginx configuration files.

**Implementation**:

```php
<?php

declare(strict_types=1);

namespace App\Services\Installer;

use Illuminate\Support\Facades\File;
use RuntimeException;

class WebServerConfigGenerator
{
    /**
     * Generate Apache .htaccess file
     *
     * @param array{
     *     force_https?: bool,
     *     enable_compression?: bool,
     *     enable_caching?: bool
     * } $options
     * @return array{
     *     success: bool,
     *     path: string,
     *     message: string,
     *     content?: string
     * }
     */
    public function generateApacheConfig(array $options = []): array
    {
        $htaccessPath = public_path('.htaccess');

        // Check if .htaccess already exists
        if (File::exists($htaccessPath)) {
            return [
                'success' => false,
                'path' => $htaccessPath,
                'message' => '.htaccess file already exists. Manual configuration may be required.',
            ];
        }

        $content = $this->buildApacheHtaccess($options);

        try {
            File::put($htaccessPath, $content);

            return [
                'success' => true,
                'path' => $htaccessPath,
                'message' => '.htaccess file created successfully.',
                'content' => $content,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'path' => $htaccessPath,
                'message' => 'Failed to create .htaccess: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Build Apache .htaccess content
     *
     * @param array{
     *     force_https?: bool,
     *     enable_compression?: bool,
     *     enable_caching?: bool
     * } $options
     * @return string .htaccess file content
     */
    private function buildApacheHtaccess(array $options): string
    {
        $forceHttps = $options['force_https'] ?? false;
        $enableCompression = $options['enable_compression'] ?? true;
        $enableCaching = $options['enable_caching'] ?? true;

        $htaccess = <<<'HTACCESS'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

HTACCESS;

        // HTTPS redirect (optional)
        if ($forceHttps) {
            $htaccess .= <<<'HTACCESS'

    # Force HTTPS
    RewriteCond %{HTTPS} !=on
    RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

HTACCESS;
        }

        // Laravel URL rewrite rules
        $htaccess .= <<<'HTACCESS'

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

HTACCESS;

        // Security Headers
        $htaccess .= <<<'HTACCESS'

# Security Headers
<IfModule mod_headers.c>
    # Prevent clickjacking
    Header always set X-Frame-Options "SAMEORIGIN"

    # Prevent MIME type sniffing
    Header always set X-Content-Type-Options "nosniff"

    # Enable XSS Protection
    Header always set X-XSS-Protection "1; mode=block"

    # Referrer Policy
    Header always set Referrer-Policy "strict-origin-when-cross-origin"

    # Permissions Policy (formerly Feature-Policy)
    Header always set Permissions-Policy "geolocation=(), microphone=(), camera=()"

    # Remove Server Header (security through obscurity)
    Header unset Server
    Header unset X-Powered-By
</IfModule>

HTACCESS;

        // File Protection
        $htaccess .= <<<'HTACCESS'

# Protect Sensitive Files
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>

# Block access to .env file
<Files .env>
    Order allow,deny
    Deny from all
</Files>

# Block access to database directory
RedirectMatch 403 ^/database/.*$

# Block access to storage directory (except public)
RedirectMatch 403 ^/storage/(?!app/public/).*$

# Disable directory browsing
Options -Indexes

HTACCESS;

        // Compression (optional)
        if ($enableCompression) {
            $htaccess .= <<<'HTACCESS'

# Enable GZIP Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/x-javascript application/json application/xml application/rss+xml
</IfModule>

HTACCESS;
        }

        // Caching (optional)
        if ($enableCaching) {
            $htaccess .= <<<'HTACCESS'

# Browser Caching
<IfModule mod_expires.c>
    ExpiresActive On

    # Images
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"

    # CSS and JavaScript
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType application/x-javascript "access plus 1 month"

    # Fonts
    ExpiresByType font/woff "access plus 1 year"
    ExpiresByType font/woff2 "access plus 1 year"

    # Default
    ExpiresDefault "access plus 1 week"
</IfModule>

HTACCESS;
        }

        $htaccess .= <<<'HTACCESS'

# PHP Settings (if allowed by hosting)
<IfModule mod_php.c>
    php_value upload_max_filesize 50M
    php_value post_max_size 50M
    php_value max_execution_time 120
    php_value max_input_time 120
    php_value memory_limit 512M
</IfModule>
HTACCESS;

        return $htaccess;
    }

    /**
     * Generate nginx configuration example
     *
     * @return array{
     *     success: bool,
     *     path: string,
     *     message: string,
     *     content: string
     * }
     */
    public function generateNginxExample(): array
    {
        $nginxPath = base_path('nginx.conf.example');
        $content = $this->buildNginxConfig();

        try {
            File::put($nginxPath, $content);

            return [
                'success' => true,
                'path' => $nginxPath,
                'message' => 'nginx.conf.example created successfully.',
                'content' => $content,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'path' => $nginxPath,
                'message' => 'Failed to create nginx.conf.example: ' . $e->getMessage(),
                'content' => '',
            ];
        }
    }

    /**
     * Build nginx configuration content
     *
     * @return string nginx configuration
     */
    private function buildNginxConfig(): string
    {
        return <<<'NGINX'
server {
    listen 80;
    listen [::]:80;
    server_name example.com;
    root /var/www/pinecms/public;

    # Redirect HTTP to HTTPS (uncomment to enable)
    # return 301 https://$server_name$request_uri;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";
    add_header Referrer-Policy "strict-origin-when-cross-origin";

    index index.php index.html;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    # Deny access to hidden files
    location ~ /\. {
        deny all;
    }

    # Deny access to .env
    location ~ /\.env {
        deny all;
    }

    # Deny access to database directory
    location ~ ^/database/ {
        deny all;
    }

    # Deny access to storage (except public)
    location ~ ^/storage/(?!app/public/) {
        deny all;
    }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~* \.(jpg|jpeg|gif|png|css|js|ico|xml|woff|woff2)$ {
        expires 1y;
        access_log off;
        add_header Cache-Control "public, immutable";
    }
}

# HTTPS server block (uncomment and configure SSL certificates)
# server {
#     listen 443 ssl http2;
#     listen [::]:443 ssl http2;
#     server_name example.com;
#     root /var/www/pinecms/public;
#
#     ssl_certificate /etc/letsencrypt/live/example.com/fullchain.pem;
#     ssl_certificate_key /etc/letsencrypt/live/example.com/privkey.pem;
#     ssl_protocols TLSv1.2 TLSv1.3;
#     ssl_ciphers HIGH:!aNULL:!MD5;
#     ssl_prefer_server_ciphers on;
#
#     # (Copy all location blocks from HTTP server block above)
# }
NGINX;
    }

    /**
     * Detect web server type
     *
     * @return string 'apache'|'nginx'|'unknown'
     */
    public function detectWebServer(): string
    {
        $serverSoftware = $_SERVER['SERVER_SOFTWARE'] ?? '';

        if (str_contains(strtolower($serverSoftware), 'apache')) {
            return 'apache';
        }

        if (str_contains(strtolower($serverSoftware), 'nginx')) {
            return 'nginx';
        }

        return 'unknown';
    }
}
```

### Step 2: Create Web Server Config Controller

**File**: `app/Http/Controllers/Installer/WebServerController.php`

**Description**: API endpoints for web server configuration generation.

**Implementation**:

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use App\Services\Installer\WebServerConfigGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebServerController extends Controller
{
    public function __construct(
        private readonly WebServerConfigGenerator $generator
    ) {}

    /**
     * Generate Apache .htaccess file
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function generateApacheConfig(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'force_https' => 'boolean',
            'enable_compression' => 'boolean',
            'enable_caching' => 'boolean',
        ]);

        $result = $this->generator->generateApacheConfig($validated);

        return response()->json($result, $result['success'] ? 201 : 409);
    }

    /**
     * Generate nginx configuration example
     *
     * @return JsonResponse
     */
    public function generateNginxExample(): JsonResponse
    {
        $result = $this->generator->generateNginxExample();

        return response()->json($result, $result['success'] ? 201 : 500);
    }

    /**
     * Detect web server type
     *
     * @return JsonResponse
     */
    public function detectServer(): JsonResponse
    {
        $serverType = $this->generator->detectWebServer();

        return response()->json([
            'success' => true,
            'server_type' => $serverType,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
        ]);
    }
}
```

### Step 3: Add Routes

**File**: `routes/installer.php`

**Description**: Add web server configuration routes.

**Implementation**:

```php
<?php

declare(strict_types=1);

use App\Http\Controllers\Installer\AdminUserController;
use App\Http\Controllers\Installer\DatabaseController;
use App\Http\Controllers\Installer\EnvironmentController;
use App\Http\Controllers\Installer\RequirementsController;
use App\Http\Controllers\Installer\WebServerController;
use Illuminate\Support\Facades\Route;

Route::prefix('installer')->middleware('web')->group(function () {
    Route::get('/requirements', [RequirementsController::class, 'check'])
        ->name('installer.requirements.check');

    Route::post('/environment', [EnvironmentController::class, 'generate'])
        ->name('installer.environment.generate');

    Route::post('/database/initialize', [DatabaseController::class, 'initialize'])
        ->name('installer.database.initialize');

    Route::post('/database/migrate', [DatabaseController::class, 'migrate'])
        ->name('installer.database.migrate');

    Route::get('/database/info', [DatabaseController::class, 'info'])
        ->name('installer.database.info');

    Route::post('/admin-user', [AdminUserController::class, 'create'])
        ->name('installer.admin-user.create');

    Route::post('/admin-user/check-password', [AdminUserController::class, 'checkPasswordStrength'])
        ->name('installer.admin-user.check-password');

    // Web server configuration
    Route::post('/webserver/apache', [WebServerController::class, 'generateApacheConfig'])
        ->name('installer.webserver.apache');

    Route::post('/webserver/nginx', [WebServerController::class, 'generateNginxExample'])
        ->name('installer.webserver.nginx');

    Route::get('/webserver/detect', [WebServerController::class, 'detectServer'])
        ->name('installer.webserver.detect');
});
```

## 🧪 Testing Requirements

**Unit Tests:**

- `tests/Unit/Services/Installer/WebServerConfigGeneratorTest.php`
    - Test `buildApacheHtaccess()` with various options
    - Test HTTPS redirect enabled/disabled
    - Test compression enabled/disabled
    - Test caching enabled/disabled
    - Test security headers present
    - Test file protection rules
    - Test `buildNginxConfig()` generates valid config
    - Test `detectWebServer()` with Apache/nginx/unknown

**Feature Tests:**

- `tests/Feature/Installer/WebServerConfigTest.php`
    - Test POST `/installer/webserver/apache` creates .htaccess
    - Test GET `/installer/webserver/detect` detects server type
    - Test POST `/installer/webserver/nginx` creates nginx.conf.example
    - Test 409 status when .htaccess already exists
    - Test generated .htaccess is valid Apache syntax

**Example Unit Test:**

```php
<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Installer;

use App\Services\Installer\WebServerConfigGenerator;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class WebServerConfigGeneratorTest extends TestCase
{
    private WebServerConfigGenerator $generator;
    private string $testHtaccessPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generator = new WebServerConfigGenerator();
        $this->testHtaccessPath = public_path('.htaccess.test');
    }

    protected function tearDown(): void
    {
        if (File::exists($this->testHtaccessPath)) {
            File::delete($this->testHtaccessPath);
        }
        parent::tearDown();
    }

    public function test_build_apache_htaccess_includes_security_headers(): void
    {
        $content = $this->invokeMethod($this->generator, 'buildApacheHtaccess', [[]]);

        $this->assertStringContainsString('X-Frame-Options', $content);
        $this->assertStringContainsString('X-Content-Type-Options', $content);
        $this->assertStringContainsString('X-XSS-Protection', $content);
        $this->assertStringContainsString('Referrer-Policy', $content);
    }

    public function test_build_apache_htaccess_includes_file_protection(): void
    {
        $content = $this->invokeMethod($this->generator, 'buildApacheHtaccess', [[]]);

        $this->assertStringContainsString('<Files .env>', $content);
        $this->assertStringContainsString('RedirectMatch 403 ^/database/', $content);
        $this->assertStringContainsString('Options -Indexes', $content);
    }

    public function test_build_apache_htaccess_includes_https_redirect_when_enabled(): void
    {
        $content = $this->invokeMethod($this->generator, 'buildApacheHtaccess', [
            ['force_https' => true],
        ]);

        $this->assertStringContainsString('RewriteCond %{HTTPS} !=on', $content);
        $this->assertStringContainsString('https://%{HTTP_HOST}', $content);
    }

    public function test_build_apache_htaccess_excludes_https_redirect_when_disabled(): void
    {
        $content = $this->invokeMethod($this->generator, 'buildApacheHtaccess', [
            ['force_https' => false],
        ]);

        $this->assertStringNotContainsString('Force HTTPS', $content);
    }

    public function test_build_apache_htaccess_includes_compression_when_enabled(): void
    {
        $content = $this->invokeMethod($this->generator, 'buildApacheHtaccess', [
            ['enable_compression' => true],
        ]);

        $this->assertStringContainsString('mod_deflate', $content);
        $this->assertStringContainsString('AddOutputFilterByType DEFLATE', $content);
    }

    public function test_build_apache_htaccess_includes_caching_when_enabled(): void
    {
        $content = $this->invokeMethod($this->generator, 'buildApacheHtaccess', [
            ['enable_caching' => true],
        ]);

        $this->assertStringContainsString('mod_expires', $content);
        $this->assertStringContainsString('ExpiresActive On', $content);
    }

    public function test_build_nginx_config_includes_security_headers(): void
    {
        $content = $this->invokeMethod($this->generator, 'buildNginxConfig');

        $this->assertStringContainsString('add_header X-Frame-Options', $content);
        $this->assertStringContainsString('add_header X-Content-Type-Options', $content);
        $this->assertStringContainsString('add_header X-XSS-Protection', $content);
    }

    public function test_build_nginx_config_includes_file_protection(): void
    {
        $content = $this->invokeMethod($this->generator, 'buildNginxConfig');

        $this->assertStringContainsString('location ~ /\\.env', $content);
        $this->assertStringContainsString('deny all', $content);
        $this->assertStringContainsString('location ~ ^/database/', $content);
    }

    public function test_detect_web_server_returns_apache(): void
    {
        $_SERVER['SERVER_SOFTWARE'] = 'Apache/2.4.41 (Ubuntu)';

        $result = $this->generator->detectWebServer();

        $this->assertEquals('apache', $result);
    }

    public function test_detect_web_server_returns_nginx(): void
    {
        $_SERVER['SERVER_SOFTWARE'] = 'nginx/1.18.0';

        $result = $this->generator->detectWebServer();

        $this->assertEquals('nginx', $result);
    }

    private function invokeMethod(object $object, string $methodName, array $parameters = []): mixed
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
```

## 📚 Related Documentation

**PRD Specifications:**

- **Feature**: `docs/prd/05-CORE-FEATURES.md` Section 2.1 (Apache .htaccess Generator)
- **Architecture**: `docs/prd/04-ARCHITECTURE.md` Section 6 (Infrastructure Requirements)
- **Timeline**: Week 2 (v1.0.0)
- **Success Criteria**: Auto-generated .htaccess for shared hosting

**Architecture:**

- **Web Servers**: Apache (default), nginx (optional)
- **Security Headers**: OWASP best practices
- **Performance**: Compression, caching, browser cache headers

**Quality Requirements:**

- **Security**: Security headers, file protection, HTTPS redirect
- **Performance**: GZIP compression, browser caching, expires headers
- **Testing**: Unit tests for all configuration generation logic

**Related Tasks:**

- **Next**: 006-post-install-cleanup
- **Blocks**: Pretty URLs won't work without .htaccess
- **Depends On**: 004-admin-user-wizard (web server config generated after user creation)

## ✅ Quality Gates Checklist

### Code Quality

- [ ] PHPStan Level 8 passes
- [ ] Laravel Pint formatted
- [ ] `declare(strict_types=1);` in all PHP files
- [ ] PHPDoc blocks with array shapes
- [ ] No hardcoded file paths

### Testing

- [ ] Unit tests written and passing (8+ test cases)
- [ ] Feature tests written and passing
- [ ] Test coverage > 80%
- [ ] Edge cases covered (existing .htaccess, read-only directories)

### Security

- [ ] Security headers included (X-Frame-Options, X-Content-Type-Options, etc.)
- [ ] File protection rules (.env, database/, .git)
- [ ] Directory listing disabled
- [ ] HTTPS redirect optional (configurable)

### Performance

- [ ] GZIP compression enabled
- [ ] Browser caching configured (1 year for images, 1 month for CSS/JS)
- [ ] Expires headers set

### Documentation

- [ ] PHPDoc comments added
- [ ] nginx.conf.example commented
- [ ] Security headers explained

## ✅ Verification Steps

```bash
# Backend quality check
composer quality

# Test Apache config generation
curl -X POST http://localhost:8000/installer/webserver/apache \
  -H "Content-Type: application/json" \
  -d '{
    "force_https": false,
    "enable_compression": true,
    "enable_caching": true
  }' | jq

# Verify .htaccess created
cat public/.htaccess

# Test server detection
curl http://localhost:8000/installer/webserver/detect | jq

# Verify .htaccess syntax (Apache only)
apachectl configtest

# Test nginx config generation
curl -X POST http://localhost:8000/installer/webserver/nginx | jq

# Verify nginx.conf.example created
cat nginx.conf.example

# Test nginx config syntax (nginx only)
nginx -t -c nginx.conf.example
```

## 🔄 Git Workflow

```bash
# Start task
git checkout -b task-005-web-server-config

# Implement
git commit -m "feat(installer): add WebServerConfigGenerator service"
git commit -m "feat(installer): add Apache .htaccess generation"
git commit -m "feat(installer): add nginx configuration template"
git commit -m "feat(installer): add web server detection"
git commit -m "test(installer): add WebServerConfigGenerator unit tests"

# Before completing
composer quality

# Complete task
git commit -m "feat(installer): complete task-005-web-server-config

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>"
```
