<?php namespace BigName\DatabaseBackup\Commands\Database;

use BigName\DatabaseBackup\Commands\Command;
use BigName\DatabaseBackup\Databases\Database;
use BigName\DatabaseBackup\ShellProcessing\ShellProcessor;

/**
 * Class DumpDatabase
 * @package BigName\DatabaseBackup\Commands\Database\Mysql
 */
class DumpDatabase implements Command
{
    /**
     * @var string
     */
    private $outputPath;
    /**
     * @var \BigName\DatabaseBackup\ShellProcessing\ShellProcessor
     */
    private $shellProcessor;
    /**
     * @var \BigName\DatabaseBackup\Databases\Database
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
