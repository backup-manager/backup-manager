<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Filesystems;

class Destination
{
    private FilesystemAdapter $destinationFilesystem;
    private string $destinationPath;

    public function __construct(FilesystemAdapter $destinationFilesystem, string $destinationPath)
    {
        $this->destinationFilesystem = $destinationFilesystem;
        $this->destinationPath       = $destinationPath;
    }

    public function destinationFilesystem(): FilesystemAdapter
    {
        return $this->destinationFilesystem;
    }

    public function destinationPath(): string
    {
        return $this->destinationPath;
    }
}
