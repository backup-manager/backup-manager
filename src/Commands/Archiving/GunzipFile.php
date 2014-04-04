<?php namespace BigName\DatabaseBackup\Commands\Archiving; 

use BigName\DatabaseBackup\Commands\Command;
use BigName\DatabaseBackup\ShellProcessing\ShellProcessor;

class GunzipFile implements Command
{
    private $sourcePath;
    /**
     * @var \BigName\DatabaseBackup\ShellProcessing\ShellProcessor
     */
    private $shellProcessor;

    public function __construct($sourcePath, ShellProcessor $shellProcessor)
    {
        $this->sourcePath = $sourcePath;
        $this->shellProcessor = $shellProcessor;
    }

    public function execute()
    {
        return $this->shellProcessor->process($this->getCommand());
    }

    private function getCommand()
    {
        return sprintf('gunzip %s', escapeshellarg($this->sourcePath));
    }
}
