<?php

declare(strict_types=1);

namespace Fezfez\BackupManager;

use Fezfez\BackupManager\Compressors\Compressor;
use Fezfez\BackupManager\Databases\Database;
use Fezfez\BackupManager\Filesystems\BackupManagerFilesystemAdapter;
use Fezfez\BackupManager\Filesystems\Destination;

interface BackupManagerContract
{
    /** @param Destination[] $destinations */
    public function backup(BackupManagerFilesystemAdapter $localFileSystem, Database $database, array $destinations, string $localTmpPath, Compressor ...$compression): void;

    public function restore(BackupManagerFilesystemAdapter $localFileSystem, BackupManagerFilesystemAdapter $to, string $sourcePath, Database $databaseName, Compressor ...$compression): void;
}
