<?php namespace McCool\DatabaseBackup\Commands\Database\Mysql;

use McCool\DatabaseBackup\Commands\Command;
use McCool\DatabaseBackup\ShellProcessor;

/**
 * Class RestoreDatabase
 * @package McCool\DatabaseBackup\Commands\Database\Mysql
 */
class RestoreDatabase implements Command
{
    /**
     * @var string
     */
    private $inputPath;
    /**
     * @var MysqlConnection
     */
    private $connection;
    /**
     * @var \McCool\DatabaseBackup\ShellProcessor
     */
    private $shellProcessor;

    /**
     * @param string $inputPath
     * @param MysqlConnection $connection
     * @param ShellProcessor $shellProcessor
     */
    public function __construct($inputPath, MysqlConnection $connection, ShellProcessor $shellProcessor)
    {
        $this->inputPath = $inputPath;
        $this->connection = $connection;
        $this->shellProcessor = $shellProcessor;
    }

    /**
     * Execute the command.
     */
    public function execute()
    {
        $this->shellProcessor->process($this->getCommand());
    }

    /**
     * Produce the command string.
     * @return string
     */
    private function getCommand()
    {
        return sprintf('mysql -h%s -P%s -u%s -p=%s %s -e "source %s;"',
            escapeshellarg($this->connection->host),
            escapeshellarg($this->connection->port),
            escapeshellarg($this->connection->username),
            escapeshellarg($this->connection->password),
            escapeshellarg($this->connection->database),
            escapeshellarg($this->inputPath)
        );
    }
}
