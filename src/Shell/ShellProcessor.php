<?php namespace BackupManager\Shell;

use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Process\Process;

class ShellProcessor {
    /** @var Process */
    private $process;

    public function __construct(Process $process) {
        $this->process = $process;
    }

    /**
     * @param $command
     * @throws ShellProcessFailed
     * @throws LogicException
     */
    public function process(ShellCommand $command) {
        $this->process->setCommandLine($command->command());
        $this->process->setTimeout(null);
        $this->process->run();
        if ( ! $this->process->isSuccessful()) {
            throw new ShellProcessFailed($this->process->getErrorOutput());
        }
    }
}