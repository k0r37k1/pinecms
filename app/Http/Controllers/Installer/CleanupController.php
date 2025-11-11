<?php

declare(strict_types=1);

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use App\Services\Installer\PostInstallCleanup;
use Illuminate\Http\JsonResponse;

class CleanupController extends Controller
{
    public function __construct(
        private readonly PostInstallCleanup $cleanup
    ) {}

    /**
     * Run post-installation cleanup
     */
    public function cleanup(): JsonResponse
    {
        // Check if already installed
        if ($this->cleanup->isInstalled()) {
            return response()->json([
                'success' => false,
                'message' => 'Installation already completed.',
            ], 409);
        }

        $result = $this->cleanup->cleanup();

        return response()->json($result, $result['success'] ? 200 : 500);
    }

    /**
     * Get installation status
     */
    public function status(): JsonResponse
    {
        $info = $this->cleanup->getInstallationInfo();

        return response()->json([
            'success' => true,
            'installation' => $info,
        ]);
    }

    /**
     * Unlock installation (development only)
     */
    public function unlock(): JsonResponse
    {
        $result = $this->cleanup->unlock();

        return response()->json($result, $result['success'] ? 200 : 403);
    }
}
