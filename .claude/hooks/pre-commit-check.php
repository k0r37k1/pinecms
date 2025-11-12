#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * PreToolUse Hook - Test Enforcement
 *
 * This hook prevents git commits unless tests have passed.
 * It checks for a marker file created by successful test runs.
 *
 * How it works:
 * 1. Detects git commit commands
 * 2. Checks for test marker file in /tmp/pinecms-tests-passed
 * 3. Blocks commit if marker file doesn't exist
 * 4. Allows commit if marker file exists (tests passed)
 *
 * To create marker file manually:
 * - Run: composer test && npm test && touch /tmp/pinecms-tests-passed
 * - Or use: /build-and-fix command (creates marker automatically)
 */

require __DIR__ . '/../../vendor/autoload.php';

use BeyondCode\ClaudeHooks\ClaudeHook;
use BeyondCode\ClaudeHooks\Hooks\PreToolUse;

$hook = ClaudeHook::create();

if ($hook instanceof PreToolUse) {
    $data = $hook->data();

    // Check if this is a git commit command
    $tool = $data['tool'] ?? '';
    $command = $data['command'] ?? '';

    if ($tool === 'Bash' && str_contains($command, 'git commit')) {
        // Check if tests have passed
        $testMarkerFile = getTestMarkerPath();

        if (! file_exists($testMarkerFile)) {
            // Tests have not passed - block commit
            echo "\n";
            echo "❌ COMMIT BLOCKED: Tests must pass before committing!\n";
            echo "\n";
            echo "Please run one of the following:\n";
            echo "\n";
            echo "  Option 1 (Recommended):\n";
            echo "  /build-and-fix           # Fix all errors systematically\n";
            echo "\n";
            echo "  Option 2 (Quick):\n";
            echo "  composer test && npm test && touch {$testMarkerFile}\n";
            echo "\n";
            echo "  Option 3 (Full Quality):\n";
            echo "  composer quality && npm run quality && touch {$testMarkerFile}\n";
            echo "\n";
            echo "If you're certain tests have passed and want to bypass this check:\n";
            echo "  touch {$testMarkerFile}\n";
            echo "\n";

            $hook->response()->block();

            return;
        }

        // Check marker file age (must be < 10 minutes old)
        $markerAge = time() - filemtime($testMarkerFile);
        if ($markerAge > 600) { // 10 minutes
            echo "\n";
            echo "⚠️  COMMIT BLOCKED: Test marker is stale ({$markerAge} seconds old)\n";
            echo "\n";
            echo "Code may have changed since tests ran. Please re-run tests:\n";
            echo "  composer test && npm test && touch {$testMarkerFile}\n";
            echo "\n";

            $hook->response()->block();

            return;
        }

        // Tests passed and marker is fresh - allow commit
        echo "\n";
        echo "✅ Tests passed ({$markerAge}s ago) - proceeding with commit\n";
        echo "\n";

        // Optional: Remove marker file after successful commit
        // This forces tests to be run before every commit
        // Uncomment the next line to enable:
        // unlink($testMarkerFile);
    }
}

$hook->response()->continue();

/**
 * Get the path to the test marker file
 */
function getTestMarkerPath(): string
{
    return sys_get_temp_dir() . '/pinecms-tests-passed';
}
