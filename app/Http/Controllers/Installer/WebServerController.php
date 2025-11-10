<?php

declare(strict_types=1);

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use App\Services\Installer\WebServerConfigGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Web Server Configuration Controller
 *
 * Handles API endpoints for generating web server configuration files:
 * - Apache .htaccess file with OWASP security headers
 * - nginx configuration example template
 * - Web server type detection
 */
class WebServerController extends Controller
{
    /**
     * Create a new controller instance
     */
    public function __construct(
        private readonly WebServerConfigGenerator $generator
    ) {}

    /**
     * Generate Apache .htaccess file
     *
     * @return JsonResponse HTTP 201 on success, 409 if file exists, 422 on validation error
     */
    public function generateApacheConfig(Request $request): JsonResponse
    {
        /**
         * @var array{force_https?: bool, enable_compression?: bool, enable_caching?: bool} $validated
         *
         * @phpstan-ignore staticMethod.dynamicCall
         */
        $validated = $request->validate([
            'force_https' => 'nullable|boolean',
            'enable_compression' => 'nullable|boolean',
            'enable_caching' => 'nullable|boolean',
        ]);

        $result = $this->generator->generateApacheConfig($validated);

        return response()->json($result, $result['success'] ? 201 : 409);
    }

    /**
     * Generate nginx configuration example
     *
     * @return JsonResponse HTTP 201 on success, 500 on failure
     */
    public function generateNginxExample(): JsonResponse
    {
        $result = $this->generator->generateNginxExample();

        return response()->json($result, $result['success'] ? 201 : 500);
    }

    /**
     * Detect web server type
     *
     * @return JsonResponse HTTP 200 with server detection results
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
