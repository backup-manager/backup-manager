<?php namespace McCool\DatabaseBackup\Processors;

use Symfony\Component\Process\Process;

class ShellProcessor implements ProcessorInterface
{
    /**
     * The Symfony Process instance.
     *
     * @var \Symfony\Component\Process\Process
     */
    protected $process;

    public function __construct(Process $process)
    {
        $this->process = $process;
    }

    /**
     * Executes the given command.
     *
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
     *
     * @return string|null
     */
    public function getErrors()
    {
        return $this->process->getErrorOutput();
    }
}