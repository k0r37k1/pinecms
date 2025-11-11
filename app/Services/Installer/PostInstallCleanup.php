<?php

declare(strict_types=1);

namespace App\Services\Installer;

use Illuminate\Support\Facades\File;

class PostInstallCleanup
{
    /**
     * Lock file name
     */
    private const LOCK_FILE = '.installed';

    /**
     * Check if installation is complete
     */
    public function isInstalled(): bool
    {
        return File::exists(base_path(self::LOCK_FILE));
    }

    /**
     * Create installation lock file
     *
     * @throws \RuntimeException If lock file creation fails
     */
    private function createLockFile(): void
    {
        $lockPath = base_path(self::LOCK_FILE);

        $lockData = [
            'installed_at' => now()->toIso8601String(),
            'version' => config('app.version', '1.0.0'),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
        ];

        $content = json_encode($lockData, JSON_PRETTY_PRINT);

        if ($content === false || File::put($lockPath, $content) === false) {
            throw new \RuntimeException('Failed to create lock file');
        }

        chmod($lockPath, 0444); // Read-only
    }

    /**
     * Update .env file to mark installation complete
     *
     * @throws \RuntimeException If .env update fails
     */
    private function updateEnvFile(): void
    {
        $envPath = base_path('.env');

        if (! File::exists($envPath)) {
            throw new \RuntimeException('.env file not found');
        }

        $envContent = File::get($envPath);

        // Update PINECMS_INSTALLED flag
        if (str_contains($envContent, 'PINECMS_INSTALLED=')) {
            $envContent = (string) preg_replace(
                '/PINECMS_INSTALLED=.*/i',
                'PINECMS_INSTALLED=true',
                $envContent
            );
        } else {
            $envContent .= "\nPINECMS_INSTALLED=true\n";
        }

        // Disable installer
        if (str_contains($envContent, 'PINECMS_INSTALLER_DISABLED=')) {
            $envContent = (string) preg_replace(
                '/PINECMS_INSTALLER_DISABLED=.*/i',
                'PINECMS_INSTALLER_DISABLED=true',
                $envContent
            );
        } else {
            $envContent .= "PINECMS_INSTALLER_DISABLED=true\n";
        }

        if (File::put($envPath, $envContent) === false) {
            throw new \RuntimeException('Failed to update .env file');
        }
    }

    /**
     * Run post-installation cleanup
     *
     * @return array{
     *     success: bool,
     *     message: string,
     *     actions_completed: array<string, bool>,
     *     errors?: array<string, string>
     * }
     */
    public function cleanup(): array
    {
        $actions = [
            'env_updated' => false,
            'lock_file_created' => false,
            'installer_routes_disabled' => false,
        ];

        $errors = [];

        // Step 1: Update .env file
        try {
            $this->updateEnvFile();
            $actions['env_updated'] = true;
        } catch (\RuntimeException $e) {
            $errors['env_update'] = $e->getMessage();
        }

        // Step 2: Create lock file
        try {
            $this->createLockFile();
            $actions['lock_file_created'] = true;
        } catch (\RuntimeException $e) {
            $errors['lock_file'] = $e->getMessage();
        }

        // Step 3: Mark installer as disabled
        $actions['installer_routes_disabled'] = true;

        $success = count($errors) === 0;

        return [
            'success' => $success,
            'message' => $success
                ? 'Installation completed successfully. Installer has been locked.'
                : 'Installation completed with errors. Manual cleanup may be required.',
            'actions_completed' => $actions,
            'errors' => $errors,
        ];
    }

    /**
     * Get installation information
     *
     * @return array{
     *     installed: bool,
     *     installed_at?: string,
     *     version?: string,
     *     php_version?: string,
     *     laravel_version?: string
     * }|null
     */
    public function getInstallationInfo(): ?array
    {
        $lockPath = base_path(self::LOCK_FILE);

        if (! File::exists($lockPath)) {
            return [
                'installed' => false,
            ];
        }

        $lockData = json_decode(File::get($lockPath), true);

        return [
            'installed' => true,
            'installed_at' => $lockData['installed_at'] ?? null,
            'version' => $lockData['version'] ?? null,
            'php_version' => $lockData['php_version'] ?? null,
            'laravel_version' => $lockData['laravel_version'] ?? null,
        ];
    }

    /**
     * Unlock installation (for development/testing only)
     *
     * @return array{success: bool, message: string, actions?: array<string>}
     */
    public function unlock(): array
    {
        if (! app()->environment('local', 'testing')) {
            return [
                'success' => false,
                'message' => 'Unlock is only allowed in local or testing environments',
            ];
        }

        $actions = [];

        // Remove lock file
        $lockPath = base_path(self::LOCK_FILE);
        if (File::exists($lockPath)) {
            @chmod($lockPath, 0644); // Make writable
            File::delete($lockPath);
            $actions[] = 'Lock file removed';
        }

        // Update .env
        $envPath = base_path('.env');
        if (File::exists($envPath)) {
            $envContent = File::get($envPath);
            $envContent = (string) preg_replace('/PINECMS_INSTALLED=true/i', 'PINECMS_INSTALLED=false', $envContent);
            $envContent = (string) preg_replace('/PINECMS_INSTALLER_DISABLED=true/i', 'PINECMS_INSTALLER_DISABLED=false', $envContent);
            File::put($envPath, $envContent);
            $actions[] = '.env updated';
        }

        return [
            'success' => true,
            'message' => 'Installation unlocked successfully',
            'actions' => $actions,
        ];
    }
}
