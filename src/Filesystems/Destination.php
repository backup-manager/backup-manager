<?php

declare(strict_types=1);

namespace BackupManager\Filesystems;

/**
 * Where a backup goes: a storage connection name and a path within it.
 */
final class Destination
{
    public function __construct(
        private readonly string $destinationFilesystem,
        private readonly string $destinationPath,
    ) {
    }

    public function destinationFilesystem(): string
    {
        return $this->destinationFilesystem;
    }

    public function destinationPath(): string
    {
        return $this->destinationPath;
    }
}
