<?php namespace McCool\DatabaseBackup\Commands;

use McCool\DatabaseBackup\Exceptions\ShellProcessFailed;
use McCool\DatabaseBackup\ShellProcessor;

abstract class Command
{
    private $processor;

    public function __construct(ShellProcessor $processor)
    {
        $this->processor = $processor;
    }

    abstract protected function getShellCommand($path);
    abstract protected function getArchivedFileName($path);

    public function execute($path)
    {
        $this->processor->process($this->getShellCommand($path));
        $this->handleProcessorErrors();
    }

    protected function handleProcessorErrors()
    {
        if ($this->processor->getErrors()) {
            throw new ShellProcessFailed($this->processor->getErrors());
        }
    }
} 
