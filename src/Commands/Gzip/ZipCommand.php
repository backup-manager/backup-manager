<?php namespace McCool\DatabaseBackup\Commands\Gzip; 

use McCool\DatabaseBackup\Commands\ShellCommand;

class ZipCommand implements ShellCommand
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function getCommand()
    {
        return "gzip {$this->path}";
    }
}
