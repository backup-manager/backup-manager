<?php namespace BigName\BackupManager\Databases;

/**
 * Class PostgresqlDatabase
 * @package BigName\BackupManager\Databases
 */
class PostgresqlDatabase extends Database
{
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
}
