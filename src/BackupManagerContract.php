<?php

declare(strict_types=1);

namespace Fezfez\BackupManager;

use Fezfez\BackupManager\Compressors\Compressor;
use Fezfez\BackupManager\Databases\Database;
use Fezfez\BackupManager\Filesystems\BackupManagerFilesystemAdapter;
use Fezfez\BackupManager\Filesystems\Destination;
use Fezfez\BackupManager\Filesystems\LocalFilesystemAdapter;

interface BackupManagerContract
{
    /** @param Destination[] $destinations */
    public function backup(
        LocalFilesystemAdapter $localFileSystem,
        Database $database,
        array $destinations,
        Compressor ...$compression,
    ): void;

    public function restore(
        LocalFilesystemAdapter $localFileSystem,
        BackupManagerFilesystemAdapter $to,
        string $sourcePath,
        Database $databaseName,
        Compressor ...$compression,
    ): void;
}
