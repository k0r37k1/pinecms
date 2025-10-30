#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * UserPromptSubmit Hook - Skills Auto-Activation
 *
 * This hook analyzes the user's prompt BEFORE Claude sees it and automatically
 * injects relevant skill reminders based on keyword/intent/file pattern matching.
 *
 * How it works:
 * 1. Read user prompt from stdin
 * 2. Load skill-rules.json configuration
 * 3. Match prompt against keywords, intent patterns, and file patterns
 * 4. Inject skill reminders into Claude's context
 * 5. Return modified prompt with skill reminders
 */

// Read stdin (hook data)
$input = json_decode(file_get_contents('php://stdin'), true);
$userMessage = $input['userMessage'] ?? '';

if (empty($userMessage)) {
    // No prompt, nothing to do
    outputResponse();
}

// Load skill rules configuration
$skillRulesFile = __DIR__ . '/../skill-rules.json';
if (! file_exists($skillRulesFile)) {
    // No skill rules configured, pass through
    outputResponse();
}

$skillRules = json_decode(file_get_contents($skillRulesFile), true);
$skills = $skillRules['skills'] ?? [];

// Find relevant skills based on prompt
$relevantSkills = findRelevantSkills($userMessage, $skills);

if (empty($relevantSkills)) {
    // No skills matched, pass through
    outputResponse();
}

// Format skill reminder
$skillReminder = formatSkillReminder($relevantSkills);

// Inject skill reminder into Claude's context
$modifiedMessage = $skillReminder . "\n\n" . $userMessage;

// Output the modified response
outputResponse($modifiedMessage);

/**
 * Find skills that match the user's prompt
 */
function findRelevantSkills(string $prompt, array $skills): array
{
    $relevant = [];
    $promptLower = strtolower($prompt);

    foreach ($skills as $skillName => $config) {
        $score = 0;

        // Check keywords
        $keywords = $config['promptTriggers']['keywords'] ?? [];
        foreach ($keywords as $keyword) {
            if (str_contains($promptLower, strtolower($keyword))) {
                $score += 2;
            }
        }

        // Check intent patterns (regex)
        $patterns = $config['promptTriggers']['intentPatterns'] ?? [];
        foreach ($patterns as $pattern) {
            if (preg_match('/' . $pattern . '/i', $prompt)) {
                $score += 3;
            }
        }

        // Check file triggers (if prompt mentions file paths)
        $pathPatterns = $config['fileTriggers']['pathPatterns'] ?? [];
        foreach ($pathPatterns as $pathPattern) {
            // Convert glob pattern to regex
            $regex = str_replace(['**/', '*'], ['.*', '[^/]*'], $pathPattern);
            if (preg_match('/' . $regex . '/i', $prompt)) {
                $score += 2;
            }
        }

        // If skill matched, add to relevant list
        if ($score > 0) {
            $relevant[] = [
                'name' => $skillName,
                'config' => $config,
                'score' => $score,
            ];
        }
    }

    // Sort by priority, then by score
    usort($relevant, function ($a, $b) {
        $priorities = ['critical' => 4, 'high' => 3, 'medium' => 2, 'low' => 1];
        $aPriority = $priorities[$a['config']['priority']] ?? 0;
        $bPriority = $priorities[$b['config']['priority']] ?? 0;

        if ($aPriority !== $bPriority) {
            return $bPriority - $aPriority;
        }

        return $b['score'] - $a['score'];
    });

    return array_slice($relevant, 0, 3); // Max 3 skills to avoid overwhelming
}

/**
 * Format skill reminder for injection
 */
function formatSkillReminder(array $relevantSkills): string
{
    $reminder = "🎯 SKILL ACTIVATION CHECK\n\n";
    $reminder .= "The following skills may be relevant for this request:\n\n";

    foreach ($relevantSkills as $skill) {
        $name = $skill['name'];
        $config = $skill['config'];
        $instructionFile = $config['instructionFile'] ?? 'N/A';

        $reminder .= "   • **{$name}**\n";
        $reminder .= "     📄 See: {$instructionFile}\n";
        $reminder .= "     Priority: {$config['priority']}\n\n";
    }

    $reminder .= "Please review these guidelines before proceeding with implementation.\n";

    return $reminder;
}

/**
 * Output response in the format Claude Code expects
 */
function outputResponse(?string $modifiedMessage = null): void
{
    $response = [
        'proceed' => true,
    ];

    if ($modifiedMessage !== null) {
        $response['userMessage'] = $modifiedMessage;
    }

    echo json_encode($response);
    exit(0);
}
