<?php namespace BackupManager\Tasks\Database;

use BackupManager\Tasks\Task;
use BackupManager\Databases\Database;
use BackupManager\ShellProcessing\ShellProcessor;

/**
 * Class RestoreDatabase
 * @package BackupManager\Tasks\Database
 */
class RestoreDatabase implements Task {

    /** @var string */
    private $inputPath;
    /** @var ShellProcessor */
    private $shellProcessor;
    /** @var Database */
    private $database;

    /**
     * @param Database $database
     * @param $inputPath
     * @param ShellProcessor $shellProcessor
     */
    public function __construct(Database $database, $inputPath, ShellProcessor $shellProcessor) {
        $this->inputPath = $inputPath;
        $this->shellProcessor = $shellProcessor;
        $this->database = $database;
    }

    /**
     * @throws \BackupManager\ShellProcessing\ShellProcessFailed
     */
    public function execute() {
        return $this->shellProcessor->process($this->database->getRestoreCommandLine($this->inputPath));
    }
}
