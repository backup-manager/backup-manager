<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Filesystems;

class Destination
{
    public function __construct(
        private readonly BackupManagerFilesystemAdapter $destinationFilesystem,
        private readonly string $destinationPath,
    ) {
    }

    public function destinationFilesystem(): BackupManagerFilesystemAdapter
    {
        return $this->destinationFilesystem;
    }

    public function destinationPath(): string
    {
        return $this->destinationPath;
    }
}
