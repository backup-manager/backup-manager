<?php

declare(strict_types=1);

namespace BackupManager\Tasks\Compression;

use BackupManager\Compressors\Compressor;
use BackupManager\ShellProcessing\ShellProcessFailed;
use BackupManager\ShellProcessing\ShellProcessor;
use BackupManager\Tasks\Task;
use Symfony\Component\Process\Process;

final class DecompressFile implements Task
{
    public function __construct(
        private readonly Compressor $compressor,
        private readonly string $sourcePath,
        private readonly ShellProcessor $shellProcessor,
    ) {
    }

    /**
     * @throws ShellProcessFailed
     */
    #[\Override]
    public function execute(): void
    {
        $this->shellProcessor->process(
            Process::fromShellCommandline($this->compressor->getDecompressCommandLine($this->sourcePath))
        );
    }
}
