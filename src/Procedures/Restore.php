<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Procedures;

use Fezfez\BackupManager\Compressors\Compressor;
use Fezfez\BackupManager\Databases\Database;
use Fezfez\BackupManager\Filesystems\FilesystemAdapter;
use Fezfez\BackupManager\ShellProcessing\ShellProcessor;
use Fezfez\BackupManager\Tasks\Compression\DecompressFile;
use Fezfez\BackupManager\Tasks\Database\RestoreDatabase;

use function basename;
use function sprintf;
use function uniqid;

class Restore implements RestoreProcedure
{
    private DecompressFile $decompressFile;
    private RestoreDatabase $restoreDatabase;

    public function __construct(
        DecompressFile $decompressFile,
        RestoreDatabase $restoreDatabase
    ) {
        $this->decompressFile  = $decompressFile;
        $this->restoreDatabase = $restoreDatabase;
    }

    public static function create(): self
    {
        return new self(
            new DecompressFile(),
            new RestoreDatabase(new ShellProcessor())
        );
    }

    public function __invoke(FilesystemAdapter $from, FilesystemAdapter $to, string $sourcePath, Database $databaseName, Compressor ...$compression): void
    {
        // begin the life of a new working file
        $workingFile = sprintf('%s/%s', basename($sourcePath), uniqid());

        // download or retrieve the archived backup file

        $to->writeStream($sourcePath, $from->readStream(basename($workingFile)));

        // decompress the archived backup
        $workingFile = $this->decompressFile->__invoke($workingFile, ...$compression);

        $this->restoreDatabase->__invoke($databaseName, $workingFile);

        $from->delete(basename($workingFile));
    }
}
