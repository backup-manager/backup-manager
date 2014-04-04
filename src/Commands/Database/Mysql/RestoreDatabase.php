<?php namespace BigName\DatabaseBackup\Commands\Database\Mysql;

use BigName\DatabaseBackup\Commands\Command;
use BigName\DatabaseBackup\Connections\MysqlConnection;
use BigName\DatabaseBackup\ShellProcessing\ShellProcessor;

class RestoreDatabase implements Command
{
    private $connection;
    private $inputPath;
    private $shellProcessor;

    public function __construct(MysqlConnection $connection, $inputPath, ShellProcessor $shellProcessor)
    {
        $this->inputPath = $inputPath;
        $this->connection = $connection;
        $this->shellProcessor = $shellProcessor;
    }

    public function execute()
    {
        return $this->shellProcessor->process($this->getCommand());
    }

    private function getCommand()
    {
        return sprintf('mysql --host=%s --port=%s --user=%s --password=%s %s -e "source %s;"',
            escapeshellarg($this->connection->host),
            escapeshellarg($this->connection->port),
            escapeshellarg($this->connection->username),
            escapeshellarg($this->connection->password),
            escapeshellarg($this->connection->database),
            $this->inputPath
        );
    }
}
