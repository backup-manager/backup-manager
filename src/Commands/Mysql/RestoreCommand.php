<?php namespace McCool\DatabaseBackup\Commands\Mysql; 

use McCool\DatabaseBackup\Commands\Command;

class RestoreCommand implements Command
{
    private $path;
    private $details;

    public function __construct($path, ConnectionDetails $details)
    {
        $this->path = $path;
        $this->details = $details;
    }

    public function getShellCommand()
    {
        return sprintf('mysql -h%s -P%s -u%s -p=%s %s -e "source %s;"',
            escapeshellarg($this->details->host),
            escapeshellarg($this->details->port),
            escapeshellarg($this->details->username),
            escapeshellarg($this->details->password),
            escapeshellarg($this->details->database),
            escapeshellarg($this->path)
        );
    }
}
