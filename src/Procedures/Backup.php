<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Procedures;

use Fezfez\BackupManager\Compressors\Compressor;
use Fezfez\BackupManager\Databases\Database;
use Fezfez\BackupManager\Filesystems\Destination;
use Fezfez\BackupManager\Filesystems\FilesystemAdapter;
use Fezfez\BackupManager\ShellProcessing\ShellProcessor;
use Fezfez\BackupManager\Tasks\Compression\CompressFile;
use Fezfez\BackupManager\Tasks\Database\DumpDatabase;

use function basename;
use function sprintf;
use function uniqid;

class Backup implements BackupProcedure
{
    private DumpDatabase $dumpDatabase;
    private CompressFile $compressFile;

    public function __construct(
        DumpDatabase $dumpDatabase,
        CompressFile $compressFile,
    ) {
        $this->dumpDatabase = $dumpDatabase;
        $this->compressFile = $compressFile;
    }

    public static function create(): self
    {
        return new self(
            new DumpDatabase(new ShellProcessor()),
            new CompressFile()
        );
    }

    /** @param Destination[] $destinations */
    public function __invoke(FilesystemAdapter $from, Database $database, array $destinations, string $localTmpPath, Compressor ...$compressor): void
    {
        $tmpPath = sprintf('%s/%s', $localTmpPath, uniqid());

        $this->dumpDatabase->__invoke($database, $tmpPath);
        $filePath = $this->compressFile->__invoke($tmpPath, ...$compressor);

        // upload the archive
        foreach ($destinations as $destination) {
            $destination->destinationFilesystem()->writeStream($destination->destinationPath(), $from->readStream(basename($filePath)));
        }

        // cleanup the local archive
        $from->delete(basename($filePath));
    }
}
