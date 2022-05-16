<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Procedures;

use Fezfez\BackupManager\Compressors\Compressor;
use Fezfez\BackupManager\Databases\Database;
use Fezfez\BackupManager\Filesystems\BackupManagerFilesystemAdapter;

interface RestoreProcedure
{
    public function __invoke(BackupManagerFilesystemAdapter $localFileSystem, BackupManagerFilesystemAdapter $to, string $sourcePath, Database $databaseName, Compressor ...$compression): void;
}
