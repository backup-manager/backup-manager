<?php namespace BigName\DatabaseBackup\Databases;

interface Database
{
    public function __construct(array $config);
    public function getDumpCommandLine($inputPath);
    public function getRestoreCommandLine($outputPath);
}
