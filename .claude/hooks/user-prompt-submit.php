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

// Check if MCP priority reminder should be injected
$shouldInjectMcp = shouldInjectMcpPriority($userMessage, $relevantSkills);

// If no skills matched and no MCP reminder needed, pass through
if (empty($relevantSkills) && ! $shouldInjectMcp) {
    outputResponse();
}

// Build modified message
$modifiedMessage = '';

// Add skill reminder if skills were found
if (! empty($relevantSkills)) {
    $modifiedMessage .= formatSkillReminder($relevantSkills);
}

// Add MCP priority reminder if needed
if ($shouldInjectMcp) {
    if (! empty($modifiedMessage)) {
        $modifiedMessage .= "\n";
    }
    $modifiedMessage .= getMcpPriorityReminder();
}

// Append original user message
$modifiedMessage .= "\n\n" . $userMessage;

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
            // Escape the pattern if it's not already a valid regex
            $escapedPattern = preg_quote($pattern, '/');
            if (@preg_match('/' . $escapedPattern . '/i', $prompt)) {
                $score += 3;
            }
        }

        // Check file triggers (if prompt mentions file paths)
        $pathPatterns = $config['fileTriggers']['pathPatterns'] ?? [];
        foreach ($pathPatterns as $pathPattern) {
            // Convert glob pattern to regex - escape first, then replace wildcards
            $regex = preg_quote($pathPattern, '/');
            $regex = str_replace(['\\*\\*/', '\\*'], ['.*', '[^/]*'], $regex);
            if (@preg_match('/' . $regex . '/i', $prompt)) {
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
 * Check if MCP priority reminder should be injected
 */
function shouldInjectMcpPriority(string $prompt, array $relevantSkills): bool
{
    // Check if prompt explicitly mentions Superpowers skills
    if (preg_match('/superpowers:/i', $prompt)) {
        return true;
    }

    // Check if any triggered skills are Superpowers skills
    foreach ($relevantSkills as $skill) {
        if (str_starts_with($skill['name'], 'superpowers:')) {
            return true;
        }
    }

    // Check if prompt mentions agents (Task tool usage)
    if (preg_match('/(use|launch|run|invoke)\s+(the\s+)?([\w-]+\s+)?agent/i', $prompt)) {
        return true;
    }
    if (preg_match('/Task\s+tool/i', $prompt)) {
        return true;
    }
    if (preg_match('/subagent|sub-agent/i', $prompt)) {
        return true;
    }

    // Check for generic agent mentions (let Claude/Superpowers decide which agent)
    if (preg_match('/\bagents?\b/i', $prompt)) {
        return true;
    }
    if (preg_match('/agent\//i', $prompt)) {
        return true;
    }

    // Check if prompt mentions instruction files
    if (preg_match('/\.claude\/instructions\//i', $prompt)) {
        return true;
    }
    if (preg_match('/(backend|frontend|testing|security|quality|architecture)\.md/i', $prompt)) {
        return true;
    }

    // Check for generic instructions mentions
    if (preg_match('/\binstructions?\b/i', $prompt)) {
        return true;
    }
    if (preg_match('/instructions?\//i', $prompt)) {
        return true;
    }

    // Check if prompt mentions agent files
    if (preg_match('/\.claude\/agents\//i', $prompt)) {
        return true;
    }

    // Check if prompt mentions CLAUDE.md files
    if (preg_match('/claude\.md/i', $prompt)) {
        return true;
    }
    if (preg_match('/CLAUDE\.md/i', $prompt)) {
        return true;
    }

    // Check if prompt mentions following guidelines/instructions
    if (preg_match('/follow(ing)?\s+(the\s+)?(guidelines|instructions|standards)/i', $prompt)) {
        return true;
    }

    return false;
}

/**
 * Get MCP Priority reminder for Superpowers workflows
 */
function getMcpPriorityReminder(): string
{
    $reminder = "🔧 MCP SERVER PRIORITY\n\n";
    $reminder .= "Use MCP servers in this order for optimal results:\n\n";
    $reminder .= "   1. **Laravel Boost** - Laravel/PHP ecosystem\n";
    $reminder .= "      Tools: search-docs, tinker, database-query, browser-logs\n\n";
    $reminder .= "   2. **filesystem** - Flat-file operations\n";
    $reminder .= "      Tools: read_text_file, write_file, directory_tree\n\n";
    $reminder .= "   3. **Laravel MCP Companion** - Alternative Laravel docs\n";
    $reminder .= "      Tools: search_laravel_docs_with_context, read_laravel_doc_content\n\n";
    $reminder .= "   4. **context7** - Modern framework documentation\n";
    $reminder .= "      Tools: resolve-library-id, get-library-docs (PrimeVue, TipTap, Alpine)\n\n";
    $reminder .= "   5. **brave-search** - Web search for latest information\n";
    $reminder .= "      Tools: brave_web_search\n\n";
    $reminder .= "   6. **firecrawl** - Advanced web scraping\n";
    $reminder .= "      Tools: firecrawl_scrape, firecrawl_search, firecrawl_map\n\n";
    $reminder .= "Prefer tools from higher-priority servers when multiple options exist.\n";

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
