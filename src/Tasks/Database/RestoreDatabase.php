<?php namespace BackupManager\Tasks\Database;

use BackupManager\Tasks\Task;
use BackupManager\Databases\Database;
use Symfony\Component\Process\Process;
use BackupManager\ShellProcessing\ShellProcessor;
use BackupManager\ShellProcessing\ShellProcessFailed;

/**
 * Class RestoreDatabase
 * @package BackupManager\Tasks\Database
 */
class RestoreDatabase implements Task
{
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
        return $this->shellProcessor->process(
            Process::fromShellCommandline(
                $this->database->getRestoreCommandLine($this->inputPath)
            )
        );
    }
}
