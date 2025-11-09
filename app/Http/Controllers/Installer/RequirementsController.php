<?php

declare(strict_types=1);

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use App\Services\Installer\RequirementsChecker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RequirementsController extends Controller
{
    /**
     * Check system requirements for PineCMS installation
     */
    public function check(Request $request, RequirementsChecker $checker): JsonResponse
    {
        $requirements = $checker->checkAll();

        $includeInstructions = $request->boolean('include_instructions', false);

        $response = [
            'requirements' => $requirements,
        ];

        if ($includeInstructions) {
            $response['instructions'] = $checker->getResolutionInstructions($requirements);
        }

        return response()->json($response);
    }
}
