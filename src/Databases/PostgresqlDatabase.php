<?php namespace BigName\BackupManager\Databases;
use BigName\BackupManager\Config\Config;

/**
 * Class PostgresqlDatabase
 * @package BigName\BackupManager\Databases
 */
class PostgresqlDatabase implements Database
{
    /**
     * @var array
     */
    private $config;

    /**
     * @param $type
     * @return bool
     */
    public function handles($type)
    {
        return strtolower($type) == 'postgresql';
    }

    /**
     * @param array $config
     * @return null
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param $outputPath
     * @return string
     */
    public function getDumpCommandLine($outputPath)
    {
        return sprintf('pg_dump --host=%s --port=%s --username=%s %s -f %s',
            escapeshellarg($this->config['host']),
            escapeshellarg($this->config['port']),
            escapeshellarg($this->config['user']),
            escapeshellarg($this->config['database']),
            escapeshellarg($outputPath)
        );
    }

    /**
     * @param $inputPath
     * @return string
     */
    public function getRestoreCommandLine($inputPath)
    {
        return sprintf('psql --host=%s --port=%s --user=%s %s -f %s',
            escapeshellarg($this->config['host']),
            escapeshellarg($this->config['port']),
            escapeshellarg($this->config['user']),
            escapeshellarg($this->config['database']),
            escapeshellarg($inputPath)
        );
    }

    /**
     * @return PostgresqlDatabase
     */
    private function getDatabase()
    {
        $database = new PostgresqlDatabase;
        return $database;
    }
}
