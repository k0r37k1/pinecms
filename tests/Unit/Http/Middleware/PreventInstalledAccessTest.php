<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\PreventInstalledAccess;
use App\Services\Installer\PostInstallCleanup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PreventInstalledAccessTest extends TestCase
{
    /**
     * Test middleware allows access when not installed
     */
    public function testMiddlewareAllowsAccessWhenNotInstalled(): void
    {
        // Mock PostInstallCleanup to return not installed
        $cleanup = $this->createMock(PostInstallCleanup::class);
        $cleanup->method('isInstalled')->willReturn(false);

        $this->app->instance(PostInstallCleanup::class, $cleanup);

        // Create test route
        Route::get('/test-installer', fn () => 'installer page')
            ->middleware(PreventInstalledAccess::class);

        $response = $this->get('/test-installer');

        $response->assertStatus(200);
        $response->assertSee('installer page');
    }

    /**
     * Test middleware redirects when already installed
     */
    public function testMiddlewareRedirectsWhenAlreadyInstalled(): void
    {
        // Mock PostInstallCleanup to return installed
        $cleanup = $this->createMock(PostInstallCleanup::class);
        $cleanup->method('isInstalled')->willReturn(true);

        $middleware = new PreventInstalledAccess($cleanup);

        $request = Request::create('/installer/test', 'GET');
        $next = fn ($req) => response('should not reach here');

        $response = $middleware->handle($request, $next);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('/admin/login', $response->headers->get('Location'));
    }
}
