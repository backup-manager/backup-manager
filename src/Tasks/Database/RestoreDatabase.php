<?php

declare(strict_types=1);

namespace BackupManager\Tasks\Database;

use BackupManager\Databases\Database;
use BackupManager\ShellProcessing\ShellProcessFailed;
use BackupManager\ShellProcessing\ShellProcessor;
use BackupManager\Tasks\Task;
use Symfony\Component\Process\Process;

final class RestoreDatabase implements Task
{
    public function __construct(
        private readonly Database $database,
        private readonly string $inputPath,
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
            Process::fromShellCommandline($this->database->getRestoreCommandLine($this->inputPath))
        );
    }
}
