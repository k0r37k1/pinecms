<?php

declare(strict_types=1);

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use App\Services\Installer\EnvironmentGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Handles environment file generation during installation.
 */
class EnvironmentController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private readonly EnvironmentGenerator $generator
    ) {}

    /**
     * Generate the .env file with validated configuration.
     *
     * @throws ValidationException
     */
    public function generate(Request $request): JsonResponse
    {
        /** @phpstan-ignore staticMethod.dynamicCall */
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url|max:255',
            'admin_email' => 'nullable|email|max:255',
            'db_name' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9_-]+$/',
            'timezone' => 'nullable|string|timezone:all',
        ]);
        /** @var array{app_name: string, app_url: string, admin_email?: string, db_name?: string, timezone?: string} $validated */
        $validation = $this->generator->validateConfig($validated);

        if (! $validation['valid']) {
            throw ValidationException::withMessages($validation['errors']);
        }

        $result = $this->generator->generate($validated);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'env_path' => $result['path'],
        ], $result['success'] ? 201 : 409);
    }
}
