<?php namespace McCool\DatabaseBackup\Mysql;

use McCool\DatabaseBackup\Dumpers\DumperInterface;
use McCool\DatabaseBackup\RestorerInterface;
use McCool\DatabaseBackup\Shell\ShellProcessorException;
use McCool\DatabaseBackup\Shell\ShellProcessorInterface;

/**
 * Class Mysql
 * @package McCool\DatabaseBackup\Mysql
 */
class Mysql implements DumperInterface, RestorerInterface
{
    /**
     * The processor instance.
     *
     * @var \McCool\DatabaseBackup\Shell\ShellProcessorInterface
     */
    protected $processor;
    /**
     * @var \McCool\DatabaseBackup\Mysql\MysqlConnectionDetails
     */
    private $connectionDetails;

    /**
     * @param \McCool\DatabaseBackup\Shell\ShellProcessorInterface $processor
     * @param MysqlConnectionDetails $connectionDetails
     */
    public function __construct(ShellProcessorInterface $processor, MysqlConnectionDetails $connectionDetails)
    {
        $this->processor = $processor;
        $this->connectionDetails = $connectionDetails;
    }

    /**
     * @param string $destinationPath
     * @throws \McCool\DatabaseBackup\Shell\ShellProcessorException
     */
    public function dump($destinationPath)
    {
        $this->processor->process($this->getDumpCommand($destinationPath));
        $this->handleProcessorErrors();
    }

    /**
     * @param string $sourceFilePath
     * @return null
     * @throws \McCool\DatabaseBackup\Shell\ShellProcessorException
     */
    public function restoreFromFile($sourceFilePath)
    {
        $this->processor->process($this->getRestoreCommand($sourceFilePath));
        $this->handleProcessorErrors();
    }

    /**
     * Returns the mysqldump command with arguments.
     * @param string $destinationPath
     * @return string
     */
    protected function getDumpCommand($destinationPath)
    {
        return sprintf('mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s',
            escapeshellarg($this->connectionDetails->host),
            escapeshellarg($this->connectionDetails->port),
            escapeshellarg($this->connectionDetails->username),
            escapeshellarg($this->connectionDetails->password),
            escapeshellarg($this->connectionDetails->database),
            escapeshellarg($destinationPath)
        );
    }

    /**
     * @param $sourceFilePath
     * @return string
     */
    protected function getRestoreCommand($sourceFilePath)
    {
        return sprintf('mysql -h%s -P%s -u%s -p=%s %s -e "source %s;"',
            escapeshellarg($this->connectionDetails->host),
            escapeshellarg($this->connectionDetails->port),
            escapeshellarg($this->connectionDetails->username),
            escapeshellarg($this->connectionDetails->password),
            escapeshellarg($this->connectionDetails->database),
            escapeshellarg($sourceFilePath)
        );
    }

    private function handleProcessorErrors()
    {
        if ($this->processor->getErrors() && (!($this->processor->getErrors() != "Warning: Using a password on the command line interface can be insecure\n."))) {
            throw new ShellProcessorException($this->processor->getErrors());
        }
    }
}
