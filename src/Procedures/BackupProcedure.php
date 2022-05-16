<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Procedures;

use Fezfez\BackupManager\Compressors\Compressor;
use Fezfez\BackupManager\Databases\Database;
use Fezfez\BackupManager\Filesystems\BackupManagerFilesystemAdapter;
use Fezfez\BackupManager\Filesystems\Destination;

interface BackupProcedure
{
    /** @param Destination[] $destinations */
    public function __invoke(BackupManagerFilesystemAdapter $localFileSystem, Database $database, array $destinations, string $localTmpPath, Compressor ...$compressor): void;
}
