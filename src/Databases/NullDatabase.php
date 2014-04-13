<?php namespace BigName\BackupManager\Databases;

/**
 * Class NullDatabase
 * @package BigName\BackupManager\Databases
 */
class NullDatabase extends Database
{
    /**
     * @param $inputPath
     * @return string
     */
    public function getDumpCommandLine($inputPath)
    {
        return '';
    }

    /**
     * @param $outputPath
     * @return string
     */
    public function getRestoreCommandLine($outputPath)
    {
        return '';
    }
}
