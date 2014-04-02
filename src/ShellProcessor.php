<?php namespace McCool\DatabaseBackup;

use McCool\DatabaseBackup\Commands\Command;
use Symfony\Component\Process\Process;

/**
 * Class CommandProcessor
 * @package McCool\DatabaseBackup
 */
class ShellProcessor
{
    /**
     * @var \Symfony\Component\Process\Process
     */
    private $process;

    /**
     * @param Process $process
     */
    public function __construct(Process $process)
    {
        $this->process = $process;
    }

    public function process($command)
    {
        $this->process->setCommandLine($command);
        $this->process->run();
        if ( ! $this->process->isSuccessful()) {
            throw new ShellProcessFailed($this->process->getErrorOutput());
        }
    }
}
