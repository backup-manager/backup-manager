<?php namespace BigName\BackupManager\Tasks\Database;

use BigName\BackupManager\Tasks\Task;
use BigName\BackupManager\Databases\Database;
use BigName\BackupManager\ShellProcessing\ShellProcessor;

/**
 * Class RestoreDatabase
 * @package BigName\BackupManager\Tasks\Database
 */
class RestoreDatabase implements Task
{
    /**
     * @var string
     */
    private $inputPath;
    /**
     * @var \BigName\BackupManager\ShellProcessing\ShellProcessor
     */
    private $shellProcessor;
    /**
     * @var \BigName\BackupManager\Databases\Database
     */
    private $database;

    /**
     * @param Database $database
     * @param $inputPath
     * @param ShellProcessor $shellProcessor
     */
    public function __construct(Database $database, $inputPath, ShellProcessor $shellProcessor)
    {
        $this->inputPath = $inputPath;
        $this->shellProcessor = $shellProcessor;
        $this->database = $database;
    }

    /**
     * @throws \BigName\BackupManager\ShellProcessing\ShellProcessFailed
     */
    public function execute()
    {
        return $this->shellProcessor->process($this->database->getRestoreCommandLine($this->inputPath));
    }
}
