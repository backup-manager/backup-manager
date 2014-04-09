<?php namespace BigName\BackupManager\Commands\Database;

use BigName\BackupManager\Commands\Command;
use BigName\BackupManager\Databases\Database;
use BigName\BackupManager\ShellProcessing\ShellProcessor;

class RestoreDatabase implements Command
{
    private $inputPath;
    private $shellProcessor;
    /**
     * @var \BigName\BackupManager\Databases\Database
     */
    private $database;

    public function __construct(Database $database, $inputPath, ShellProcessor $shellProcessor)
    {
        $this->inputPath = $inputPath;
        $this->shellProcessor = $shellProcessor;
        $this->database = $database;
    }

    public function execute()
    {
        return $this->shellProcessor->process($this->database->getRestoreCommandLine($this->inputPath));
    }
}
