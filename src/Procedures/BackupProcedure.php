<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Procedures;

use Fezfez\BackupManager\Compressors\Compressor;
use Fezfez\BackupManager\Databases\Database;
use Fezfez\BackupManager\Filesystems\Destination;
use Fezfez\BackupManager\Filesystems\LocalFilesystemAdapter;

interface BackupProcedure
{
    /** @param Destination[] $destinations */
    public function __invoke(
        LocalFilesystemAdapter $localFileSystem,
        Database $database,
        array $destinations,
        Compressor ...$compressor,
    ): void;
}
