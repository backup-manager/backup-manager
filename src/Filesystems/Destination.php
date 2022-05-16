<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Filesystems;

class Destination
{
    private BackupManagerFilesystemAdapter $destinationFilesystem;
    private string $destinationPath;

    public function __construct(BackupManagerFilesystemAdapter $destinationFilesystem, string $destinationPath)
    {
        $this->destinationFilesystem = $destinationFilesystem;
        $this->destinationPath       = $destinationPath;
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
