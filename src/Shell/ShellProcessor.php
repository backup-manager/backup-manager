<?php namespace McCool\DatabaseBackup\Shell;

use McCool\DatabaseBackup\CommandProcessor;
use Symfony\Component\Process\Process;

/**
 * Class CommandProcessor
 * @package McCool\DatabaseBackup\Shell
 */
class ShellProcessor implements \McCool\DatabaseBackup\CommandProcessor
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

    /**
     * Executes the given command.
     * @param  string  $command
     * @return void
     */
    public function process($command)
    {
        $this->process->setCommandLine($command);
        $this->process->run();
    }

    /**
     * Returns errors which happened during the command execution.
     * @return string|null
     */
    public function getErrors()
    {
        return $this->process->getErrorOutput();
    }
}
