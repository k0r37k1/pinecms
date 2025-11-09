<?php

declare(strict_types=1);

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use App\Services\Installer\DatabaseInitializer;
use Illuminate\Http\JsonResponse;

class DatabaseController extends Controller
{
    /**
     * Initialize the SQLite database
     */
    public function initialize(DatabaseInitializer $initializer): JsonResponse
    {
        $result = $initializer->initialize();

        $statusCode = $result['success'] ? 201 : 500;

        return response()->json($result, $statusCode);
    }

    /**
     * Run database migrations
     */
    public function migrate(DatabaseInitializer $initializer): JsonResponse
    {
        $result = $initializer->runMigrations();

        $statusCode = $result['success'] ? 200 : 500;

        return response()->json($result, $statusCode);
    }

    /**
     * Get database information
     */
    public function info(DatabaseInitializer $initializer): JsonResponse
    {
        $info = $initializer->getDatabaseInfo();

        return response()->json([
            'success' => true,
            'database' => $info,
        ]);
    }
}
