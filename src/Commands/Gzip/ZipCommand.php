<?php namespace McCool\DatabaseBackup\Commands\Gzip; 

use McCool\DatabaseBackup\Commands\Command;

class ZipCommand extends Command
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function getShellCommand()
    {
        return "gzip {$this->path}";
    }
}
