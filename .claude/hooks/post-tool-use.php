#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * PostToolUse Hook - Track File Edits
 *
 * This hook tracks files that are edited or written during the session,
 * storing them in .claude/.edit-log.json for later processing by the Stop hook.
 */

require __DIR__ . '/../../vendor/autoload.php';

use BeyondCode\ClaudeHooks\ClaudeHook;
use BeyondCode\ClaudeHooks\Hooks\PostToolUse;

$hook = ClaudeHook::create();

if ($hook instanceof PostToolUse) {
    $toolName = $hook->toolName();

    // Only track Edit and Write operations
    if (in_array($toolName, ['Edit', 'Write'])) {
        $filePath = $hook->toolInput('file_path', '');

        if ($filePath) {
            // Normalize file path
            $filePath = str_replace(getcwd() . '/', '', $filePath);

            // Determine area (frontend/backend)
            $area = detectArea($filePath);

            // Load existing log
            $logFile = __DIR__ . '/../.edit-log.json';
            $editLog = file_exists($logFile)
                ? json_decode(file_get_contents($logFile), true)
                : ['files' => [], 'areas' => []];

            // Add file if not already tracked
            if (! in_array($filePath, $editLog['files'])) {
                $editLog['files'][] = $filePath;
            }

            // Add area if not already tracked
            if ($area !== 'other' && ! in_array($area, $editLog['areas'])) {
                $editLog['areas'][] = $area;
            }

            // Save log
            file_put_contents($logFile, json_encode($editLog, JSON_PRETTY_PRINT));
        }
    }
}

// Always continue
$hook->response()->continue();

/**
 * Detect which area of the codebase a file belongs to
 */
function detectArea(string $filePath): string
{
    // Frontend indicators
    if (
        str_contains($filePath, 'resources/js/') ||
        preg_match('/\.(vue|ts|tsx|jsx)$/', $filePath)
    ) {
        return 'frontend';
    }

    // Backend indicators
    if (
        str_contains($filePath, 'app/') ||
        str_contains($filePath, 'routes/') ||
        str_contains($filePath, 'config/') ||
        preg_match('/\.php$/', $filePath)
    ) {
        return 'backend';
    }

    return 'other';
}
