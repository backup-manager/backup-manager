<?php namespace BigName\BackupManager\Tasks\Database;

use BigName\BackupManager\Tasks\Task;
use BigName\BackupManager\Databases\Database;
use BigName\BackupManager\Shell\ShellProcessor;
use BigName\BackupManager\Shell\ShellProcessFailed;

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
     * @var ShellProcessor
     */
    private $shellProcessor;
    /**
     * @var Database
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
     * @throws ShellProcessFailed
     */
    public function execute()
    {
        return $this->shellProcessor->process($this->database->getRestoreCommandLine($this->inputPath));
    }
}
