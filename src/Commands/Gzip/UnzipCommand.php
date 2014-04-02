<?php namespace McCool\DatabaseBackup\Commands\Gzip; 

use McCool\DatabaseBackup\Commands\ShellCommand;

class UnzipCommand implements ShellCommand
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function getCommand()
    {
        return "gunzip {$this->path}";
    }
}
