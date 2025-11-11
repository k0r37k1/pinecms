<?php

declare(strict_types=1);

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Installer\SetSchedulerModeRequest;
use App\Services\Installer\CronDetector;
use Illuminate\Http\JsonResponse;

class CronController extends Controller
{
    public function __construct(
        private readonly CronDetector $detector
    ) {}

    /**
     * Detect cron availability
     */
    public function detect(): JsonResponse
    {
        $detection = $this->detector->detect();

        return response()->json([
            'success' => true,
            'data' => $detection,
        ]);
    }

    /**
     * Set scheduler mode
     */
    public function setMode(SetSchedulerModeRequest $request): JsonResponse
    {
        try {
            $this->detector->setSchedulerMode($request->input('mode'));

            return response()->json([
                'success' => true,
                'message' => 'Scheduler mode updated successfully',
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test cron execution
     */
    public function test(): JsonResponse
    {
        $result = $this->detector->testCronExecution();

        return response()->json($result);
    }
}
