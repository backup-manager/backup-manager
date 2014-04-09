<?php namespace BigName\BackupManager\Databases;

class NullDatabase extends Database
{
    public function getDumpCommandLine($inputPath)
    {
        return '';
    }

    public function getRestoreCommandLine($outputPath)
    {
        return '';
    }
}
