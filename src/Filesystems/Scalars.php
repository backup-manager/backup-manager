<?php

declare(strict_types=1);

namespace BackupManager\Filesystems;

/**
 * Casts config values to the scalar types Flysystem's strictly typed option
 * classes require; config read from environment variables arrives as strings.
 *
 * @internal
 */
final class Scalars
{
    /**
     * @param array<string, mixed> $config
     * @param list<string> $integers
     * @param list<string> $booleans
     * @return array<string, mixed>
     */
    public static function cast(array $config, array $integers, array $booleans): array
    {
        foreach ($integers as $key) {
            if (isset($config[$key])) {
                $config[$key] = (int) $config[$key];
            }
        }

        foreach ($booleans as $key) {
            if (isset($config[$key])) {
                $config[$key] = filter_var($config[$key], FILTER_VALIDATE_BOOLEAN);
            }
        }

        return $config;
    }
}
