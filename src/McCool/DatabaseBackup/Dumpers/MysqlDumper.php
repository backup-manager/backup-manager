<?php namespace McCool\DatabaseBackup\Dumpers;

use McCool\DatabaseBackup\Processors\ProcessorException;
use McCool\DatabaseBackup\Processors\ProcessorInterface;

class MysqlDumper implements DumperInterface
{
    /**
     * The processor instance.
     *
     * @var \McCool\DatabaseBackup\Processors\ProcessorInterface
     */
    private $processor;

    /**
     * The database host.
     *
     * @var string
     */
    private $host;

    /**
     * The database port number.
     *
     * @var string
     */
    private $port;

    /**
     * The database username.
     *
     * @var string
     */
    private $username;

    /**
     * The database password.
     *
     * @var string
     */
    private $password;

    /**
     * The database name.
     *
     * @var string
     */
    private $database;

    /**
     * The backup filename.
     *
     * @var string
     */
    private $destinationPath;

    /**
     * Initializes the MysqlDumper instance.
     *
     * @param  \McCool\DatabaseBackup\Processors\ProcessorInterface
     * @param  string  $host
     * @param  string  $port
     * @param  string  $username
     * @param  string  $database
     * @param  string  $destinationPath
     * @return self
     */
    public function __construct(ProcessorInterface $processor, $host, $port, $username, $password, $database, $destinationPath)
    {
        $this->processor       = $processor;
        $this->host            = $host;
        $this->port            = $port;
        $this->username        = $username;
        $this->password        = $password;
        $this->database        = $database;
        $this->destinationPath = $destinationPath;
    }

    /**
     * Dumps the backup into the database.
     *
     * @return void.
     */
    public function dump()
    {
        $this->process();
    }

    /**
     * Returns the filename for the backup.
     *
     * @return string
     */
    public function getOutputFilename()
    {
        return $this->destinationPath;
    }

    /**
     * Executes the process command.
     *
     * @return void
     * @throws \McCool\DatabaseBackup\Processors\ProcessorException
     */
    private function process()
    {
        $this->processor->process($this->getCommand());

        if ($this->processor->getErrors()) {
            throw new ProcessorException($this->processor->getErrors());
        }
    }

    /**
     * Returns the mysqldump command with arguments.
     *
     * @return string
     */
    private function getCommand()
    {
        return sprintf('mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s',
            escapeshellarg($this->host),
            escapeshellarg($this->port),
            escapeshellarg($this->username),
            escapeshellarg($this->password),
            escapeshellarg($this->database),
            escapeshellarg($this->destinationPath)
        );
    }
}