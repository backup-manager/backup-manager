<?php namespace McCool\DatabaseBackup\Commands\Gzip; 

use McCool\DatabaseBackup\Commands\Command;

class UnzipCommand extends Command
{
    protected function getShellCommand($path)
    {
        return "gunzip {$path}";
    }

    protected function getArchivedFileName($path)
    {
        return "{$path}.gz";
    }
}
