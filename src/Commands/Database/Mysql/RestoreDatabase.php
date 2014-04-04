<?php namespace BigName\DatabaseBackup\Commands\Database\Mysql;

use BigName\DatabaseBackup\Commands\Command;
use BigName\DatabaseBackup\ShellProcessor;

class RestoreDatabase implements Command
{
    private $connection;
    private $inputPath;

    public function __construct(MysqlConnection $connection, $inputPath)
    {
        $this->inputPath = $inputPath;
        $this->connection = $connection;
    }

    public function execute()
    {
        return (new ShellProcessor(new Process('')))->process($this->getCommand());
    }

    private function getCommand()
    {
        return sprintf('mysql -h%s -P%s -u%s -p=%s %s -e "source %s;"',
            escapeshellarg($this->connection->host),
            escapeshellarg($this->connection->port),
            escapeshellarg($this->connection->username),
            escapeshellarg($this->connection->password),
            escapeshellarg($this->connection->database),
            escapeshellarg($this->inputPath)
        );
    }
}
