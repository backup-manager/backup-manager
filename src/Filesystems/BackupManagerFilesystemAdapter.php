<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Filesystems;

interface BackupManagerFilesystemAdapter
{
    public function readStream(string $path): BackupManagerRessource;

    public function writeStream(string $path, BackupManagerRessource $resource): void;

    public function delete(string $path): void;
}
