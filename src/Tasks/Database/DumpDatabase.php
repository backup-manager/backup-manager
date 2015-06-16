<?php namespace BackupManager\Tasks\Database;

use BackupManager\Tasks\Task;
use BackupManager\Databases\Database;
use BackupManager\ShellProcessing\ShellProcessor;

/**
 * Class DumpDatabase
 * @package BackupManager\Tasks\Database\Mysql
 */
class DumpDatabase implements Task {

    /** @var string */
    private $outputPath;
    /** @var ShellProcessor */
    private $shellProcessor;
    /** @var Database */
    private $database;

    /**
     * @param Database $database
     * @param $outputPath
     * @param ShellProcessor $shellProcessor
     */
    public function __construct(Database $database, $outputPath, ShellProcessor $shellProcessor) {
        $this->outputPath = $outputPath;
        $this->shellProcessor = $shellProcessor;
        $this->database = $database;
    }

    /**
     * @throws \BackupManager\ShellProcessing\ShellProcessFailed
     */
    public function execute() {
        return $this->shellProcessor->process($this->database->getDumpCommandLine($this->outputPath));
    }
}
