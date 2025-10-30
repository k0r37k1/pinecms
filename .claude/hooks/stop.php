#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Stop Hook - Build Checks & Error Reminders
 *
 * This hook runs after Claude finishes responding and performs:
 * 1. Build checks (TypeScript for frontend, PHPStan for backend)
 * 2. Error detection and gentle reminders for error handling
 * 3. Cleanup of edit log
 */

require __DIR__ . '/../../vendor/autoload.php';

use BeyondCode\ClaudeHooks\ClaudeHook;
use BeyondCode\ClaudeHooks\Hooks\Stop;

$hook = ClaudeHook::create();

if ($hook instanceof Stop) {
    $logFile = __DIR__ . '/../.edit-log.json';

    // Check if any files were edited this session
    if (! file_exists($logFile)) {
        $hook->response()->continue();

        return;
    }

    $editLog = json_decode(file_get_contents($logFile), true);
    $files = $editLog['files'] ?? [];
    $areas = $editLog['areas'] ?? [];

    if (empty($files)) {
        $hook->response()->continue();

        return;
    }

    $output = "\n📝 Edited " . count($files) . " file(s) this session\n";
    $output .= '📦 Modified areas: ' . implode(', ', $areas) . "\n\n";

    $hasErrors = false;
    $errorMessages = [];

    // Run TypeScript check if frontend files were modified
    if (in_array('frontend', $areas)) {
        $output .= "🔍 Running TypeScript check...\n";
        exec('npx tsc --noEmit 2>&1', $tsOutput, $tsExitCode);

        if ($tsExitCode !== 0) {
            $hasErrors = true;
            $errorMessages[] = '❌ TypeScript: ' . count($tsOutput) . ' error(s) found';
            $errorMessages[] = '   Run: npx tsc --noEmit';
        } else {
            $output .= "✅ TypeScript: No errors\n";
        }
    }

    // Run PHPStan check if backend files were modified
    if (in_array('backend', $areas)) {
        $output .= "\n🔍 Running PHPStan check...\n";
        exec('composer analyse 2>&1', $phpstanOutput, $phpstanExitCode);

        if ($phpstanExitCode !== 0) {
            $hasErrors = true;
            // Parse PHPStan output for error count
            $phpstanText = implode("\n", $phpstanOutput);
            preg_match('/(\d+) error/', $phpstanText, $matches);
            $errorCount = $matches[1] ?? 'multiple';

            $errorMessages[] = "❌ PHPStan: {$errorCount} error(s) found";
            $errorMessages[] = '   Run: composer analyse';
        } else {
            $output .= "✅ PHPStan: No errors\n";
        }
    }

    // Check for risky patterns in edited files
    $riskyPatterns = detectRiskyPatterns($files);
    if (! empty($riskyPatterns)) {
        $output .= "\n💡 Detected risky patterns in edited files:\n";
        foreach ($riskyPatterns as $file => $patterns) {
            $output .= "   • {$file}: " . implode(', ', $patterns) . "\n";
        }
        $output .= "\n   Remember: Always wrap risky operations in try-catch blocks!\n";
    }

    // Show error summary if any
    if ($hasErrors) {
        $output .= "\n" . implode("\n", $errorMessages) . "\n";
        $output .= "\n💡 Please fix these errors before continuing.\n";
    }

    // Clean up log for next session
    unlink($logFile);

    // Output the summary
    echo $output;
}

// Always continue
$hook->response()->continue();

/**
 * Detect risky patterns in edited files
 */
function detectRiskyPatterns(array $files): array
{
    $riskyPatterns = [];

    foreach ($files as $file) {
        $fullPath = getcwd() . '/' . $file;

        if (! file_exists($fullPath)) {
            continue;
        }

        $content = file_get_contents($fullPath);
        $patterns = [];

        // Check for try-catch blocks (good!)
        if (preg_match('/try\s*\{/', $content) && preg_match('/catch\s*\(/', $content)) {
            $patterns[] = 'try-catch blocks (good!)';
        }

        // Check for database operations without try-catch
        if (
            (str_contains($content, 'DB::') || str_contains($content, '->save()'))
            && ! preg_match('/try\s*\{/', $content)
        ) {
            $patterns[] = 'database operations (needs error handling)';
        }

        // Check for API calls without try-catch
        if (
            (str_contains($content, 'Http::') || str_contains($content, 'curl_exec'))
            && ! preg_match('/try\s*\{/', $content)
        ) {
            $patterns[] = 'API calls (needs error handling)';
        }

        // Check for job dispatching
        if (str_contains($content, '::dispatch(')) {
            $patterns[] = 'job dispatching';
        }

        if (! empty($patterns)) {
            $riskyPatterns[$file] = $patterns;
        }
    }

    return $riskyPatterns;
}
