<?php namespace McCool\DatabaseBackup\Dumpers;

use McCool\DatabaseBackup\Processors\ProcessorInterface;
use McCool\DatabaseBackup\Processors\ProcessorException;

class MysqlDumper implements DumperInterface
{
    private $processor;
    private $host;
    private $port;
    private $username;
    private $password;
    private $database;
    private $destinationPath;

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

    public function dump()
    {
        $this->process();
    }

    public function getOutputFilename()
    {
        return $this->destinationPath;
    }

    private function process()
    {
        $this->processor->process($this->getCommand());

        if ($this->processor->getErrors()) {
            throw new ProcessorException($this->processor->getErrors());
        }
    }

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