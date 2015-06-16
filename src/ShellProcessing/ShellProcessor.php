<?php namespace BackupManager\ShellProcessing;

use Symfony\Component\Process\Process;

/**
 * Class CommandProcessor
 * @package BackupManager
 */
class ShellProcessor {

    /** @var Process */
    private $process;

    /**
     * @param Process $process
     */
    public function __construct(Process $process) {
        $this->process = $process;
    }

    /**
     * @param $command
     * @throws ShellProcessFailed
     * @throws \Symfony\Component\Process\Exception\LogicException
     */
    public function process($command) {
        if (empty($command)) {
            return;
        }

        $this->process->setCommandLine($command);
        $this->process->run();
        if ( ! $this->process->isSuccessful()) {
            throw new ShellProcessFailed($this->process->getErrorOutput());
        }
    }
}
