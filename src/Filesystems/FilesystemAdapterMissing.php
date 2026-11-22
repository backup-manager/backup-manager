<?php

declare(strict_types=1);

namespace BackupManager\Filesystems;

/**
 * Thrown when a filesystem type is configured but the Composer package that
 * provides its Flysystem adapter is not installed.
 */
class FilesystemAdapterMissing extends \RuntimeException
{
    public static function forType(string $type, string $package): self
    {
        return new self(sprintf(
            'The filesystem type "%s" requires an adapter package that is not installed. Run: composer require %s',
            $type,
            $package
        ));
    }
}
