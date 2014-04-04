<?php namespace BigName\DatabaseBackup\Procedures;

use BigName\DatabaseBackup\Commands\Command;
use BigName\DatabaseBackup\Commands\CommandFactory;
use BigName\DatabaseBackup\Config;
use BigName\DatabaseBackup\Filesystems\FilesystemProvider;

/**
 * Class Procedure
 * @package Procedures
 */
abstract class Procedure
{
    private $sequence;
    /**
     * @var \BigName\DatabaseBackup\Filesystems\FilesystemProvider
     */
    protected $filesystemProvider;
    /**
     * @var \BigName\DatabaseBackup\Config
     */
    protected $databaseConfig;

    public function __construct(FilesystemProvider $filesystemProvider, Config $databaseConfig)
    {
        $this->sequence = new Sequence;
        $this->filesystemProvider = $filesystemProvider;
        $this->databaseConfig = $databaseConfig;
    }

    protected function add(Command $command)
    {
        $this->sequence->add($command);
    }

    protected function execute()
    {
        $this->sequence->execute();
    }
} 
