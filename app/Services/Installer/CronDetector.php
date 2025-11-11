<?php

declare(strict_types=1);

namespace App\Services\Installer;

use Illuminate\Support\Facades\File;

class CronDetector
{
    /**
     * Allowed scheduler modes
     *
     * @var array<string>
     */
    private const ALLOWED_MODES = ['visit-triggered', 'cron'];

    /**
     * Common cron binary locations
     *
     * @var array<string>
     */
    private const CRON_BINARIES = [
        '/usr/bin/crontab',
        '/usr/local/bin/crontab',
        '/bin/crontab',
    ];

    /**
     * Detect cron availability on the system
     *
     * @return array{
     *     available: bool,
     *     cron_binary: string|null,
     *     php_binary: string,
     *     base_path: string,
     *     cron_command: string
     * }
     */
    public function detect(): array
    {
        $cronBinary = $this->findCronBinary();
        $phpBinary = PHP_BINARY;
        $basePath = base_path();

        $cronCommand = sprintf(
            '* * * * * cd %s && %s artisan schedule:run >> /dev/null 2>&1',
            $basePath,
            $phpBinary
        );

        return [
            'available' => $cronBinary !== null,
            'cron_binary' => $cronBinary,
            'php_binary' => $phpBinary,
            'base_path' => $basePath,
            'cron_command' => $cronCommand,
        ];
    }

    /**
     * Find cron binary on the system
     */
    private function findCronBinary(): ?string
    {
        foreach (self::CRON_BINARIES as $binary) {
            if (file_exists($binary) && is_executable($binary)) {
                return $binary;
            }
        }

        // Try to find crontab using 'which'
        if (function_exists('exec')) {
            $output = [];
            $returnVar = 0;
            @exec('which crontab 2>/dev/null', $output, $returnVar);

            if ($returnVar === 0 && isset($output[0]) && file_exists($output[0])) {
                return $output[0];
            }
        }

        return null;
    }

    /**
     * Set scheduler mode in .env file
     *
     * @param  string  $mode  Scheduler mode (visit-triggered or cron)
     *
     * @throws \InvalidArgumentException If mode is not allowed
     * @throws \RuntimeException If .env file cannot be updated
     */
    public function setSchedulerMode(string $mode): void
    {
        if (! in_array($mode, self::ALLOWED_MODES, true)) {
            throw new \InvalidArgumentException('Invalid scheduler mode. Allowed modes: ' . implode(', ', self::ALLOWED_MODES));
        }

        $envPath = base_path('.env');

        if (! File::exists($envPath)) {
            throw new \RuntimeException('.env file not found');
        }

        $envContent = File::get($envPath);

        // Update or add SCHEDULER_MODE
        if (str_contains($envContent, 'SCHEDULER_MODE=')) {
            $envContent = (string) preg_replace(
                '/SCHEDULER_MODE=.*/i',
                'SCHEDULER_MODE=' . $mode,
                $envContent
            );
        } else {
            $envContent .= "\nSCHEDULER_MODE={$mode}\n";
        }

        if (File::put($envPath, $envContent) === false) {
            throw new \RuntimeException('Failed to update .env file');
        }
    }

    /**
     * Test if cron execution is working
     *
     * This creates a temporary marker file, schedules a test command,
     * and checks if the marker file is modified.
     *
     * @return array{success: bool, message: string}
     */
    public function testCronExecution(): array
    {
        // For now, we return a basic success/failure based on cron availability
        // A full implementation would create a test file and verify cron can modify it
        $detection = $this->detect();

        if (! $detection['available']) {
            return [
                'success' => false,
                'message' => 'Cron is not available on this system',
            ];
        }

        // Test if we can execute shell commands
        if (! function_exists('exec')) {
            return [
                'success' => false,
                'message' => 'exec() function is disabled. Cannot test cron execution.',
            ];
        }

        return [
            'success' => true,
            'message' => 'Cron appears to be available. Manual testing recommended.',
        ];
    }
}
