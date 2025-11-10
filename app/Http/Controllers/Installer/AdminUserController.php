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
use Inertia\Response;

class AdminUserController extends Controller
{
    public function __construct(
        private readonly AdminUserCreator $creator
    ) {}

    /**
     * Show admin user creation wizard
     */
    public function show(): Response
    {
        return Inertia::render('Installer/AdminUserWizard');
    }

    /**
     * Create admin user
     */
    public function create(CreateAdminUserRequest $request): JsonResponse
    {
        /** @var array{name: string, email: string, password: string} $validated */
        $validated = $request->validated();
        $result = $this->creator->create($validated);

        if (! $result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'errors' => $result['errors'] ?? [],
            ], 422);
        }

        // PHPStan: At this point, success is true, so user must exist
        assert(isset($result['user']));

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
