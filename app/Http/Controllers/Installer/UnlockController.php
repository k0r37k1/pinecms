<?php

declare(strict_types=1);

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;

/**
 * Unlock installer for E2E testing purposes.
 *
 * This controller provides an endpoint to reset the installation state
 * by removing installation artifacts. Only available in testing environment.
 */
class UnlockController extends Controller
{
    /**
     * Unlock installer by removing installation artifacts.
     *
     * Security: Only works in testing environment.
     */
    public function unlock(): JsonResponse
    {
        // Security: Only allow when application is in testing mode
        // This is set via TESTING environment variable in phpunit.xml
        // and cached in config/app.php 'testing' key for config:cache compatibility
        if (! (bool) config('app.testing')) {
            abort(403, 'Unlock endpoint only available in testing environment');
        }

        // Delete installation lock file
        $this->deleteIfExists(base_path('.installed'));

        // Delete environment configuration
        $this->deleteIfExists(base_path('.env'));

        // Delete SQLite database
        $this->deleteIfExists(database_path('pinecms.sqlite'));

        return response()->json([
            'success' => true,
            'message' => 'Installation unlocked successfully',
        ]);
    }

    /**
     * Delete file if it exists.
     */
    private function deleteIfExists(string $path): void
    {
        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
