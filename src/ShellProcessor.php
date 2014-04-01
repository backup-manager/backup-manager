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

    /**
     * Executes the given command.
     * @param  string  $command
     * @return void
     */
    public function process(Command $command)
    {
        $this->process->setCommandLine($command->getShellCommand());
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
