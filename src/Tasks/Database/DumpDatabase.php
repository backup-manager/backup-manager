<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Tasks\Database;

use Fezfez\BackupManager\Databases\Database;
use Fezfez\BackupManager\ShellProcessing\ShellProcessor;
use Symfony\Component\Process\Process;

class DumpDatabase
{
    private ShellProcessor $shellProcessor;

    public function __construct(ShellProcessor $shellProcessor)
    {
        $this->shellProcessor = $shellProcessor;
    }

    public function __invoke(Database $database, string $outputPath): void
    {
        $this->shellProcessor->__invoke(
            Process::fromShellCommandline(
                $database->getDumpCommandLine($outputPath)
            )
        );
    }
}
