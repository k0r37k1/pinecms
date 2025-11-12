<?php

declare(strict_types=1);

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Installer\CreateAdminUserRequest;
use App\Services\Installer\AdminUserCreator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Admin User Controller
 *
 * Handles admin user creation during PineCMS installation wizard.
 * Provides endpoints for rendering the wizard UI and processing admin user creation.
 */
class AdminUserController extends Controller
{
    /**
     * Create a new controller instance
     *
     * @param  AdminUserCreator  $creator  Service for creating admin users
     */
    public function __construct(
        private readonly AdminUserCreator $creator
    ) {}

    /**
     * Show admin user creation wizard
     *
     * Renders the Inertia-powered admin user creation wizard step
     * of the PineCMS installation process.
     *
     * @return InertiaResponse Inertia response rendering the wizard
     */
    public function show(): InertiaResponse
    {
        return Inertia::render('Installer/InstallerWizard');
    }

    /**
     * Create admin user
     *
     * Processes the validated admin user creation request.
     * Returns external location redirect for web/Inertia requests or JSON for API requests.
     *
     * @param  CreateAdminUserRequest  $request  Validated admin user creation data
     * @return Response JSON response or Inertia location redirect
     */
    public function create(CreateAdminUserRequest $request): Response
    {
        /** @var array{name: string, email: string, password: string} $validated */
        $validated = $request->validated();
        $result = $this->creator->create($validated);

        // Handle failure case
        if (! $result['success']) {
            // For Inertia requests, redirect back with errors
            if ($request->hasHeader('X-Inertia')) {
                return back()->withErrors($result['errors'] ?? [])->with('message', $result['message']);
            }

            // For pure JSON API requests, return JSON error response
            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'errors' => $result['errors'] ?? [],
            ], 422);
        }

        // PHPStan: At this point, success is true, so user must exist
        assert(isset($result['user']));

        // Check if this is an Inertia request (form submission with X-Inertia header)
        // Inertia requests need external redirect via Inertia::location()
        if ($request->hasHeader('X-Inertia')) {
            // Inertia::location() returns 409 response with X-Inertia-Location header
            // The Inertia client will automatically do window.location redirect
            // Use absolute URL to ensure proper redirect handling
            return Inertia::location(url('/admin/login'));
        }

        // For pure JSON API requests (no X-Inertia header), return JSON response
        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'user' => [
                'id' => $result['user']->id,
                'name' => $result['user']->name,
                'email' => $result['user']->email,
                'role' => 'Administrator',
            ],
        ], 201);
    }

    /**
     * Check password strength (for real-time validation)
     *
     * Validates password strength in real-time during user input.
     * Returns validation status, errors, and strength score with visual indicators.
     *
     * @param  Request  $request  Request containing password to validate
     * @return JsonResponse JSON response with validation result and strength metrics
     */
    public function checkPasswordStrength(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'valid' => false,
                'errors' => ['password' => 'Password is required'],
                'strength' => [
                    'score' => 0,
                    'label' => 'None',
                    'color' => 'gray',
                ],
            ], 422);
        }

        /** @var array{password: string} $validated */
        $validated = $validator->validated();

        $validation = $this->creator->validatePasswordStrength($validated['password']);
        $strengthLabel = $this->creator->getPasswordStrengthLabel($validation['strength_score']);

        return response()->json([
            'valid' => $validation['valid'],
            'errors' => $validation['errors'],
            'strength' => [
                'score' => $validation['strength_score'],
                'label' => $strengthLabel['label'],
                'color' => $strengthLabel['color'],
            ],
        ]);
    }
}
