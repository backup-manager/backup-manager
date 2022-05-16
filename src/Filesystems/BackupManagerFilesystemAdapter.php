<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Filesystems;

interface BackupManagerFilesystemAdapter
{
    /** @throws CantReadFile */
    public function readStream(string $path): BackupManagerRessource;

    /** @throws CantWriteFile */
    public function writeStream(string $path, BackupManagerRessource $resource): void;

    /** @throws CantDeleteFile */
    public function delete(string $path): void;
}
