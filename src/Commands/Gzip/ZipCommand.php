<?php namespace McCool\DatabaseBackup\Commands\Gzip; 

use McCool\DatabaseBackup\Commands\Command;

class ZipCommand extends Command
{
    protected function getShellCommand($path)
    {
        return "gzip {$path}";
    }

    protected function getArchivedFileName($path)
    {
        return "{$path}.gz";
    }
}
