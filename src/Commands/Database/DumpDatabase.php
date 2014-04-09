<?php namespace BigName\BackupManager\Commands\Database;

use BigName\BackupManager\Commands\Command;
use BigName\BackupManager\Databases\Database;
use BigName\BackupManager\ShellProcessing\ShellProcessor;

/**
 * Class DumpDatabase
 * @package BigName\BackupManager\Commands\Database\Mysql
 */
class DumpDatabase implements Command
{
    /**
     * @var string
     */
    private $outputPath;
    /**
     * @var \BigName\BackupManager\ShellProcessing\ShellProcessor
     */
    private $shellProcessor;
    /**
     * @var \BigName\BackupManager\Databases\Database
     */
    private $database;

    public function __construct(Database $database, $outputPath, ShellProcessor $shellProcessor)
    {
        $this->outputPath = $outputPath;
        $this->shellProcessor = $shellProcessor;
        $this->database = $database;
    }

    public function execute()
    {
        return $this->shellProcessor->process($this->database->getDumpCommandLine($this->outputPath));
    }
}
