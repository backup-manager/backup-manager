<?php namespace McCool\DatabaseBackup\Commands\Gzip; 

use McCool\DatabaseBackup\Commands\Command;

class UnzipCommand implements Command
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function getShellCommand()
    {
        return "gunzip {$this->path}";
    }
}
