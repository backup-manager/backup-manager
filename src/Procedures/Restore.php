<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Procedures;

use Fezfez\BackupManager\Compressors\Compressor;
use Fezfez\BackupManager\Databases\Database;
use Fezfez\BackupManager\Filesystems\BackupManagerFilesystemAdapter;
use Fezfez\BackupManager\ShellProcessing\ShellProcessor;
use Symfony\Component\Process\Process;

use function basename;
use function sprintf;
use function uniqid;

class Restore implements RestoreProcedure
{
    private ShellProcessor $shellProcessor;

    public function __construct(ShellProcessor $shellProcessor)
    {
        $this->shellProcessor = $shellProcessor;
    }

    public static function create(): self
    {
        return new self(new ShellProcessor());
    }

    public function __invoke(BackupManagerFilesystemAdapter $localFileSystem, BackupManagerFilesystemAdapter $to, string $sourcePath, Database $databaseName, Compressor ...$compressorList): void
    {
        // begin the life of a new working file
        $workingFile = sprintf('%s/%s', basename($sourcePath), uniqid());

        // download or retrieve the archived backup file

        $to->writeStream($sourcePath, $localFileSystem->readStream(basename($workingFile)));

        // decompress the archived backup
        foreach ($compressorList as $compressor) {
            $workingFile = $compressor->decompress($workingFile);
        }

        $this->shellProcessor->__invoke(Process::fromShellCommandline($databaseName->getRestoreCommandLine($workingFile)));

        $localFileSystem->delete(basename($workingFile));
    }
}
