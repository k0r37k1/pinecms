# PineCMS

> Security & Privacy-First Content Management System

A modern CMS built with Laravel Framework 12, Vue 3, Inertia.js, and TailwindCSS.

## Quick Start

```bash
# Install
composer install
npm install

# Setup
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link

# Run
npm run dev                  # Terminal 1: Vite
php artisan serve            # Terminal 2: Laravel
```

Visit: **http://localhost:8000**

## Requirements

- PHP 8.3+
- Node.js 20+
- Composer 2.x

## Documentation

See `docs/` directory for detailed guides.

## License

MIT License - Copyright (c) 2025 [k0r37k1.dev](https://k0r37k1.dev)
