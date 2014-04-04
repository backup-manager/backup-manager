<?php namespace BigName\DatabaseBackup\Commands\Archiving;

use BigName\DatabaseBackup\Commands\Command;
use BigName\DatabaseBackup\ShellProcessing\ShellProcessor;
use Symfony\Component\Process\Process;

class GzipFile implements Command
{
    private $sourcePath;

    public function __construct($sourcePath)
    {
        $this->sourcePath = $sourcePath;
    }

    public function execute()
    {
        return (new ShellProcessor(new Process('')))->process($this->getCommand());
    }

    private function getCommand()
    {
        return sprintf('gzip %s', escapeshellarg($this->sourcePath));
    }
}
