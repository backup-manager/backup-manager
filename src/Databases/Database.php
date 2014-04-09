<?php namespace BigName\BackupManager\Databases;

abstract class Database
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    abstract public function getDumpCommandLine($inputPath);
    abstract public function getRestoreCommandLine($outputPath);
}
