<?php namespace McCool\DatabaseBackup\Commands\Mysql; 

use McCool\DatabaseBackup\Commands\Command;

class DumpCommand implements Command
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
        return sprintf('mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s',
            escapeshellarg($this->details->host),
            escapeshellarg($this->details->port),
            escapeshellarg($this->details->username),
            escapeshellarg($this->details->password),
            escapeshellarg($this->details->database),
            escapeshellarg($this->path)
        );
    }
}
