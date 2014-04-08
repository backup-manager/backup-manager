<?php namespace BigName\DatabaseBackup\Commands\Database;

use BigName\DatabaseBackup\Commands\Command;
use BigName\DatabaseBackup\Databases\Database;
use BigName\DatabaseBackup\ShellProcessing\ShellProcessor;

class RestoreDatabase implements Command
{
    private $inputPath;
    private $shellProcessor;
    /**
     * @var \BigName\DatabaseBackup\Databases\Database
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
