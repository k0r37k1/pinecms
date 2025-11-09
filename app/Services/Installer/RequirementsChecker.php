<?php

declare(strict_types=1);

namespace App\Services\Installer;

class RequirementsChecker
{
    /**
     * Required PHP version
     */
    private const REQUIRED_PHP_VERSION = '8.3.0';

    /**
     * Required PHP extensions
     *
     * @var array<int, string>
     */
    private const REQUIRED_EXTENSIONS = [
        'pdo',
        'pdo_sqlite',
        'mbstring',
        'xml',
        'curl',
        'zip',
        'fileinfo',
        'openssl',
        'tokenizer',
        'json',
        'ctype',
    ];

    /**
     * Optional PHP extensions with their benefits
     *
     * @var array<string, string>
     */
    private const OPTIONAL_EXTENSIONS = [
        'imagick' => 'Image manipulation (alternative to GD)',
        'gd' => 'Image manipulation',
        'opcache' => 'Performance optimization',
        'redis' => 'Cache and session storage',
        'intl' => 'Internationalization support',
    ];

    /**
     * Directories requiring write permissions
     *
     * @var array<int, string>
     */
    private const REQUIRED_WRITABLE_DIRECTORIES = [
        'storage',
        'storage/app',
        'storage/framework',
        'storage/framework/cache',
        'storage/framework/sessions',
        'storage/framework/views',
        'storage/logs',
        'bootstrap/cache',
        'database',
    ];

    /**
     * Minimum required memory limit in bytes (256MB)
     */
    private const REQUIRED_MEMORY_LIMIT = 268435456; // 256 * 1024 * 1024

    /**
     * Minimum required max execution time in seconds
     */
    private const REQUIRED_MAX_EXECUTION_TIME = 60;

    /**
     * Minimum required disk space in bytes (500MB)
     */
    private const REQUIRED_DISK_SPACE = 524288000; // 500 * 1024 * 1024

    /**
     * Check all requirements
     *
     * @return array{
     *     php: array{version: string, meets_requirement: bool, required_version: string},
     *     extensions: array{required: array<string, bool>, optional: array<string, array{installed: bool, benefit: string}>},
     *     permissions: array<string, array{path: string, exists: bool, writable: bool}>,
     *     environment: array{
     *         memory_limit: string,
     *         memory_limit_bytes: int,
     *         memory_limit_ok: bool,
     *         max_execution_time: int|string,
     *         max_execution_time_ok: bool,
     *         disk_space_free: int,
     *         disk_space_ok: bool
     *     },
     *     overall_status: bool
     * }
     */
    public function checkAll(): array
    {
        $php = $this->checkPhpVersion();
        $extensions = $this->checkExtensions();
        $permissions = $this->checkPermissions();
        $environment = $this->checkEnvironment();

        // Determine overall status
        $overallStatus = $php['meets_requirement']
            && $this->allRequiredExtensionsInstalled($extensions['required'])
            && $this->allDirectoriesWritable($permissions)
            && $environment['memory_limit_ok']
            && $environment['max_execution_time_ok']
            && $environment['disk_space_ok'];

        return [
            'php' => $php,
            'extensions' => $extensions,
            'permissions' => $permissions,
            'environment' => $environment,
            'overall_status' => $overallStatus,
        ];
    }

    /**
     * Get resolution instructions for failed requirements
     *
     * @param array{
     *     php: array{version: string, meets_requirement: bool, required_version: string},
     *     extensions: array{required: array<string, bool>, optional: array<string, array{installed: bool, benefit: string}>},
     *     permissions: array<string, array{path: string, exists: bool, writable: bool}>,
     *     environment: array{
     *         memory_limit: string,
     *         memory_limit_bytes: int,
     *         memory_limit_ok: bool,
     *         max_execution_time: int|string,
     *         max_execution_time_ok: bool,
     *         disk_space_free: int,
     *         disk_space_ok: bool
     *     }
     * } $requirements
     * @return array<string, string>
     */
    public function getResolutionInstructions(array $requirements): array
    {
        $instructions = [];

        // PHP version check
        if (! $requirements['php']['meets_requirement']) {
            $instructions['php_version'] = sprintf(
                'Your PHP version (%s) does not meet the minimum requirement. Please upgrade to PHP %s or higher.',
                $requirements['php']['version'],
                $requirements['php']['required_version']
            );
        }

        // Required extensions check
        foreach ($requirements['extensions']['required'] as $extension => $installed) {
            if (! $installed) {
                $instructions['extension_' . $extension] = sprintf(
                    'The required PHP extension "%s" is not installed. Please install it using your package manager or recompile PHP.',
                    $extension
                );
            }
        }

        // Permissions check
        foreach ($requirements['permissions'] as $key => $permission) {
            if (! $permission['exists']) {
                $instructions['permission_' . $key] = sprintf(
                    'The directory "%s" does not exist. Please create it.',
                    $permission['path']
                );
            } elseif (! $permission['writable']) {
                $instructions['permission_' . $key] = sprintf(
                    'The directory "%s" is not writable. Please make it writable (chmod 775).',
                    $permission['path']
                );
            }
        }

        // Environment checks
        if (! $requirements['environment']['memory_limit_ok']) {
            $instructions['memory_limit'] = sprintf(
                'The PHP memory_limit (%s) is below the recommended minimum of 256M. Please increase it in your php.ini file.',
                $requirements['environment']['memory_limit']
            );
        }

        if (! $requirements['environment']['max_execution_time_ok']) {
            $instructions['max_execution_time'] = sprintf(
                'The PHP max_execution_time (%s seconds) is below the recommended minimum of 60 seconds. Please increase it in your php.ini file.',
                (string) $requirements['environment']['max_execution_time']
            );
        }

        if (! $requirements['environment']['disk_space_ok']) {
            $instructions['disk_space'] = sprintf(
                'Available disk space (%s MB) is below the required minimum of 500 MB. Please free up disk space.',
                round($requirements['environment']['disk_space_free'] / 1024 / 1024, 2)
            );
        }

        return $instructions;
    }

    /**
     * Check PHP version requirement
     *
     * @return array{version: string, meets_requirement: bool, required_version: string}
     */
    private function checkPhpVersion(): array
    {
        $currentVersion = PHP_VERSION;
        $meetsRequirement = version_compare($currentVersion, self::REQUIRED_PHP_VERSION, '>=');

        return [
            'version' => $currentVersion,
            'meets_requirement' => $meetsRequirement,
            'required_version' => self::REQUIRED_PHP_VERSION,
        ];
    }

    /**
     * Check PHP extensions
     *
     * @return array{required: array<string, bool>, optional: array<string, array{installed: bool, benefit: string}>}
     */
    private function checkExtensions(): array
    {
        $required = [];
        foreach (self::REQUIRED_EXTENSIONS as $extension) {
            $required[$extension] = extension_loaded($extension);
        }

        $optional = [];
        foreach (self::OPTIONAL_EXTENSIONS as $extension => $benefit) {
            $optional[$extension] = [
                'installed' => extension_loaded($extension),
                'benefit' => $benefit,
            ];
        }

        return [
            'required' => $required,
            'optional' => $optional,
        ];
    }

    /**
     * Check directory permissions
     *
     * @return array<string, array{path: string, exists: bool, writable: bool}>
     */
    private function checkPermissions(): array
    {
        $permissions = [];

        foreach (self::REQUIRED_WRITABLE_DIRECTORIES as $directory) {
            $path = base_path($directory);
            $exists = file_exists($path);
            $writable = $exists && is_writable($path);

            $permissions[$directory] = [
                'path' => $path,
                'exists' => $exists,
                'writable' => $writable,
            ];
        }

        return $permissions;
    }

    /**
     * Check environment settings
     *
     * @return array{
     *     memory_limit: string,
     *     memory_limit_bytes: int,
     *     memory_limit_ok: bool,
     *     max_execution_time: int|string,
     *     max_execution_time_ok: bool,
     *     disk_space_free: int,
     *     disk_space_ok: bool
     * }
     */
    private function checkEnvironment(): array
    {
        // PHPStan: Larastan's PHPDoc says ini_get always returns string, but it can return false
        /** @phpstan-ignore function.alreadyNarrowedType */
        $memoryLimit = is_string(ini_get('memory_limit')) ? ini_get('memory_limit') : '-1';
        $memoryLimitBytes = $this->convertToBytes($memoryLimit);
        $memoryLimitOk = $memoryLimitBytes === -1 || $memoryLimitBytes >= self::REQUIRED_MEMORY_LIMIT;

        $maxExecutionTime = (int) ini_get('max_execution_time');
        $maxExecutionTimeOk = $maxExecutionTime === 0 || $maxExecutionTime >= self::REQUIRED_MAX_EXECUTION_TIME;

        $diskSpaceFree = (int) disk_free_space(base_path());
        $diskSpaceOk = $diskSpaceFree >= self::REQUIRED_DISK_SPACE;

        return [
            'memory_limit' => $memoryLimit,
            'memory_limit_bytes' => $memoryLimitBytes,
            'memory_limit_ok' => $memoryLimitOk,
            'max_execution_time' => $maxExecutionTime,
            'max_execution_time_ok' => $maxExecutionTimeOk,
            'disk_space_free' => $diskSpaceFree,
            'disk_space_ok' => $diskSpaceOk,
        ];
    }

    /**
     * Convert PHP memory notation to bytes
     *
     * Handles notations like: 256M, 2G, 512K, -1 (unlimited), or plain numbers
     */
    private function convertToBytes(string $value): int
    {
        $value = trim($value);

        // Handle unlimited memory
        if ($value === '-1') {
            return -1;
        }

        // Extract numeric value and unit
        $unit = strtoupper(substr($value, -1));
        $numericValue = (int) $value;

        // If no unit suffix or plain number, return as-is
        if (! in_array($unit, ['K', 'M', 'G'], true)) {
            return $numericValue;
        }

        // Convert based on unit
        return match ($unit) {
            'K' => $numericValue * 1024,
            'M' => $numericValue * 1024 * 1024,
            'G' => $numericValue * 1024 * 1024 * 1024,
        };
    }

    /**
     * Check if all required extensions are installed
     *
     * @param  array<string, bool>  $requiredExtensions
     */
    private function allRequiredExtensionsInstalled(array $requiredExtensions): bool
    {
        foreach ($requiredExtensions as $installed) {
            if (! $installed) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if all required directories are writable
     *
     * @param  array<string, array{path: string, exists: bool, writable: bool}>  $permissions
     */
    private function allDirectoriesWritable(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (! $permission['exists'] || ! $permission['writable']) {
                return false;
            }
        }

        return true;
    }
}
