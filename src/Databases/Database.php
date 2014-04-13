<?php namespace BigName\BackupManager\Databases;

/**
 * Class Database
 * @package BigName\BackupManager\Databases
 */
abstract class Database
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param $inputPath
     * @return string
     */
    abstract public function getDumpCommandLine($inputPath);

    /**
     * @param $outputPath
     * @return string
     */
    abstract public function getRestoreCommandLine($outputPath);
}
