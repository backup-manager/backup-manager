<?php namespace BigName\BackupManager\Databases;
use BigName\BackupManager\Config\Config;

/**
 * Class NullDatabase
 * @package BigName\BackupManager\Databases
 */
class NullDatabase implements Database
{
    /**
     * @param $type
     * @return bool
     */
    public function handles($type)
    {

    }

    /**
     * @param array $config
     * @return null
     */
    public function setConfig(array $config)
    {

    }

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
