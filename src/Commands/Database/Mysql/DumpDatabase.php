<?php namespace BigName\DatabaseBackup\Commands\Database\Mysql;

use BigName\DatabaseBackup\Commands\Command;
use BigName\DatabaseBackup\Connections\MysqlConnection;
use BigName\DatabaseBackup\ShellProcessing\ShellProcessor;
use Symfony\Component\Process\Process;

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
     * @var MysqlConnection
     */
    private $connection;

    public function __construct(MysqlConnection $connection, $outputPath)
    {
        $this->outputPath = $outputPath;
        $this->connection = $connection;
    }

    public function execute()
    {
        return (new ShellProcessor(new Process('')))->process($this->getCommand());
    }

    private function getCommand()
    {
        return sprintf('mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s',
            escapeshellarg($this->connection->host),
            escapeshellarg($this->connection->port),
            escapeshellarg($this->connection->username),
            escapeshellarg($this->connection->password),
            escapeshellarg($this->connection->database),
            escapeshellarg($this->outputPath)
        );
    }
}
