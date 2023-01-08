<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Procedures;

use Fezfez\BackupManager\Compressors\Compressor;
use Fezfez\BackupManager\Databases\Database;
use Fezfez\BackupManager\Filesystems\Destination;
use Fezfez\BackupManager\Filesystems\LocalFilesystemAdapter;
use Fezfez\BackupManager\ShellProcessing\ShellProcessor;
use Symfony\Component\Process\Process;

use function date;
use function sprintf;
use function uniqid;

final class Backup implements BackupProcedure
{
    private ShellProcessor $shellProcessor;

    public function __construct(ShellProcessor|null $shellProcessor = null)
    {
        $this->shellProcessor = $shellProcessor ?? new ShellProcessor();
    }

    /** @param Destination[] $destinations */
    public function __invoke(
        LocalFilesystemAdapter $localFileSystem,
        Database $database,
        array $destinations,
        Compressor ...$compressorList,
    ): void {
        $tmpPath = sprintf('%s/%s-%s.sql', $localFileSystem->getRootPath(), date('d-m-Y-H-i-s'), uniqid());

        $this->shellProcessor->__invoke(Process::fromShellCommandline($database->getDumpStructCommandLine($tmpPath)));
        $this->shellProcessor->__invoke(Process::fromShellCommandline($database->getDumpDataCommandLine($tmpPath)));

        foreach ($compressorList as $compressor) {
            $tmpPath = $compressor->compress($tmpPath);
        }

        // upload the archive
        foreach ($destinations as $destination) {
            $destination->destinationFilesystem()->writeStream($destination->destinationPath(), $localFileSystem->readStream($tmpPath));
        }

        // cleanup the local archive
        $localFileSystem->delete($tmpPath);
    }
}
