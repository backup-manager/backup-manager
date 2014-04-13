<?php namespace BigName\BackupManager\Databases;

/**
 * Class MysqlDatabase
 * @package BigName\BackupManager\Databases
 */
class MysqlDatabase extends Database
{
    /**
     * @param $outputPath
     * @return string
     */
    public function getDumpCommandLine($outputPath)
    {
        return sprintf('mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s',
            escapeshellarg($this->config['host']),
            escapeshellarg($this->config['port']),
            escapeshellarg($this->config['user']),
            escapeshellarg($this->config['pass']),
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
        return sprintf('mysql --host=%s --port=%s --user=%s --password=%s %s -e "source %s;"',
            escapeshellarg($this->config['host']),
            escapeshellarg($this->config['port']),
            escapeshellarg($this->config['user']),
            escapeshellarg($this->config['pass']),
            escapeshellarg($this->config['database']),
            $inputPath
        );
    }
}
