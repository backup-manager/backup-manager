<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Procedures;

use Fezfez\BackupManager\Compressors\Compressor;
use Fezfez\BackupManager\Databases\Database;
use Fezfez\BackupManager\Filesystems\BackupManagerFilesystemAdapter;
use Fezfez\BackupManager\Filesystems\LocalFilesystemAdapter;

interface RestoreProcedure
{
    public function __invoke(
        LocalFilesystemAdapter $localFileSystem,
        BackupManagerFilesystemAdapter $to,
        string $sourcePath,
        Database $databaseName,
        Compressor ...$compression,
    ): void;
}
