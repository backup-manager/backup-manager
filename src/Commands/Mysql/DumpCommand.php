<?php namespace McCool\DatabaseBackup\Commands\Mysql; 

use McCool\DatabaseBackup\Commands\ShellCommand;

class DumpCommand implements ShellCommand
{
    private $outputPath;
    private $connection;

    public function __construct($outputPath, Connection $connection)
    {
        $this->outputPath = $outputPath;
        $this->connection = $connection;
    }

    public function getCommand()
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
