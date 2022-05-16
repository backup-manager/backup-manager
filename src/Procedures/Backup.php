<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Procedures;

use Fezfez\BackupManager\Compressors\Compressor;
use Fezfez\BackupManager\Databases\Database;
use Fezfez\BackupManager\Filesystems\BackupManagerFilesystemAdapter;
use Fezfez\BackupManager\Filesystems\Destination;
use Fezfez\BackupManager\ShellProcessing\ShellProcessor;
use Symfony\Component\Process\Process;

use function basename;
use function sprintf;
use function uniqid;

class Backup implements BackupProcedure
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

    /** @param Destination[] $destinations */
    public function __invoke(BackupManagerFilesystemAdapter $localFileSystem, Database $database, array $destinations, string $localTmpPath, Compressor ...$compressorList): void
    {
        $tmpPath = sprintf('%s/%s', $localTmpPath, uniqid());

        $this->shellProcessor->__invoke(Process::fromShellCommandline($database->getDumpCommandLine($tmpPath)));

        foreach ($compressorList as $compressor) {
            $tmpPath = $compressor->compress($tmpPath);
        }

        // upload the archive
        foreach ($destinations as $destination) {
            $destination->destinationFilesystem()->writeStream($destination->destinationPath(), $localFileSystem->readStream(basename($tmpPath)));
        }

        // cleanup the local archive
        $localFileSystem->delete(basename($tmpPath));
    }
}
