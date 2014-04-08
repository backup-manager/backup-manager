<?php namespace BigName\DatabaseBackup\Databases; 

class PostgresqlDatabase extends Database
{
    public function getDumpCommandLine($outputPath)
    {
        return sprintf('pg_dump --host=%s --port=%s --username=%s %s > %s',
            escapeshellarg($this->config['host']),
            escapeshellarg($this->config['port']),
            escapeshellarg($this->config['user']),
            escapeshellarg($this->config['database']),
            escapeshellarg($outputPath)
        );
    }

    public function getRestoreCommandLine($inputPath)
    {
        return sprintf('pg_restore --host=%s --port=%s --username=%s --dbname=%s %s',
            escapeshellarg($this->config['host']),
            escapeshellarg($this->config['port']),
            escapeshellarg($this->config['user']),
            escapeshellarg($this->config['database']),
            escapeshellarg($inputPath)
        );
    }
}
