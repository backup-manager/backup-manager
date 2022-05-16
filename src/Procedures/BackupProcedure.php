<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Procedures;

use Fezfez\BackupManager\Compressors\Compressor;
use Fezfez\BackupManager\Databases\Database;
use Fezfez\BackupManager\Filesystems\Destination;
use Fezfez\BackupManager\Filesystems\FilesystemAdapter;

interface BackupProcedure
{
    /** @param Destination[] $destinations */
    public function __invoke(FilesystemAdapter $from, Database $database, array $destinations, string $localTmpPath, Compressor ...$compressor): void;
}
