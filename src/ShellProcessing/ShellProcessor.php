<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\ShellProcessing;

use Symfony\Component\Process\Process;

class ShellProcessor
{
    /**
     * @throws ShellProcessFailed
     */
    public function __invoke(Process $process): void
    {
        $process->setTimeout(500.0);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new ShellProcessFailed($process->getErrorOutput());
        }
    }
}
