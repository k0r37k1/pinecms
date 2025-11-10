<?php

declare(strict_types=1);

namespace App\Services\Installer;

/**
 * Web server configuration generator for PineCMS installation.
 *
 * Generates Apache .htaccess and nginx configuration files with security headers,
 * file protection, HTTPS redirects, and performance optimizations.
 */
class WebServerConfigGenerator
{
    /**
     * Generate Apache .htaccess file
     *
     * @param  array{force_https?: bool, enable_compression?: bool, enable_caching?: bool}  $options
     * @return array{success: bool, path: string, message: string, content?: string}
     */
    public function generateApacheConfig(array $options = []): array
    {
        $htaccessPath = public_path('.htaccess');

        if (file_exists($htaccessPath)) {
            return [
                'success' => false,
                'path' => $htaccessPath,
                'message' => '.htaccess file already exists. Manual configuration may be required.',
            ];
        }

        $content = $this->buildApacheHtaccess($options);

        try {
            file_put_contents($htaccessPath, $content);

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
     * Generate nginx configuration example
     *
     * @return array{success: bool, path: string, message: string, content: string}
     */
    public function generateNginxExample(): array
    {
        $nginxPath = base_path('nginx.conf.example');
        $content = $this->buildNginxConfig();

        try {
            file_put_contents($nginxPath, $content);

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

    /**
     * Detect if SSL is enabled on the current request
     */
    private function detectSslEnabled(): bool
    {
        return request()->secure();
    }

    /**
     * Build Apache .htaccess content
     *
     * @param  array{force_https?: bool, enable_compression?: bool, enable_caching?: bool}  $options
     * @return string .htaccess file content
     */
    private function buildApacheHtaccess(array $options): string
    {
        $forceHttps = $options['force_https'] ?? $this->detectSslEnabled();
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
     * Build nginx configuration content
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
}
