<?php

declare(strict_types=1);

namespace BackupManager\ShellProcessing;

use Symfony\Component\Process\Process;

class ShellProcessor
{
    /**
     * @return string the process output
     * @throws ShellProcessFailed
     */
    public function process(Process $process): string
    {
        $process->setTimeout(null);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ShellProcessFailed($process->getErrorOutput());
        }

        return $process->getOutput();
    }
}
