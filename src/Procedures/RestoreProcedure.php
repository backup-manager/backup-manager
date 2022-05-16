<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Procedures;

use Fezfez\BackupManager\Compressors\Compressor;
use Fezfez\BackupManager\Databases\Database;
use Fezfez\BackupManager\Filesystems\FilesystemAdapter;

interface RestoreProcedure
{
    public function __invoke(FilesystemAdapter $from, FilesystemAdapter $to, string $sourcePath, Database $databaseName, Compressor ...$compression): void;
}
