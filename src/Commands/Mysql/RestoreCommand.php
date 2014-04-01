<?php namespace McCool\DatabaseBackup\Commands\Mysql; 

use McCool\DatabaseBackup\Commands\Command;

class RestoreCommand implements Command
{
    private $path;
    private $connection;

    public function __construct($path, Connection $connection)
    {
        $this->path = $path;
        $this->connection = $connection;
    }

    public function getShellCommand()
    {
        return sprintf('mysql -h%s -P%s -u%s -p=%s %s -e "source %s;"',
            escapeshellarg($this->connection->host),
            escapeshellarg($this->connection->port),
            escapeshellarg($this->connection->username),
            escapeshellarg($this->connection->password),
            escapeshellarg($this->connection->database),
            escapeshellarg($this->path)
        );
    }
}
