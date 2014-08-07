<?php namespace BigName\BackupManager\Shell;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\LogicException;

/**
 * Class CommandProcessor
 * @package BigName\BackupManager
 */
class ShellProcessor
{
    /**
     * @var Process
     */
    private $process;

    /**
     * @param Process $process
     */
    public function __construct(Process $process)
    {
        $this->process = $process;
    }

    /**
     * @param  string $command
     * @throws ShellProcessFailed
     * @throws LogicException
     */
    public function process($command)
    {
        if (empty($command))
            return;

        $this->process->setCommandLine($command)->run();
        if ( ! $this->process->isSuccessful())
            throw new ShellProcessFailed($this->process->getErrorOutput());
    }
}
