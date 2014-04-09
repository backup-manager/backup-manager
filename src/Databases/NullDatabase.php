<?php namespace BigName\DatabaseBackup\Databases; 

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
