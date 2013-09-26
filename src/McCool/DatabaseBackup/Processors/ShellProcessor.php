<?php namespace McCool\DatabaseBackup\Processors;

use Symfony\Component\Process\Process;

class ShellProcessor implements ProcessorInterface
{
    /**
     * The Symfony Process instance.
     *
     * @var \Symfony\Component\Process\Process
     */
    private $process;

    /**
     * Executes the given command.
     *
     * @param  string  $command
     * @return void
     */
    public function process($command)
    {
        $this->process = new Process($command);
        $this->process->run();
    }

    /**
     * Determines if the process was successfully run.
     *
     * @return bool|null
     */
    public function wasSuccessful()
    {
        if ( ! isset($this->process)) return null;

        if ($this->process->isSuccessful()) {
            return true;
        }

        return false;
    }

    /**
     * Returns errors which happened during the command execution.
     *
     * @return string|null
     */
    public function getErrors()
    {
        if ( ! isset($this->process)) return null;

        return $this->process->getErrorOutput();
    }
}