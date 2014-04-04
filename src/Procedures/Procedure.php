<?php namespace BigName\DatabaseBackup\Procedures;

use BigName\DatabaseBackup\Commands\Command;
use BigName\DatabaseBackup\Commands\CommandFactory;
use BigName\DatabaseBackup\Config\Config;
use BigName\DatabaseBackup\Filesystems\FilesystemProvider;
use BigName\DatabaseBackup\ShellProcessing\ShellProcessor;

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
     * @var \BigName\DatabaseBackup\Config\Config
     */
    protected $databaseConfig;
    /**
     * @var \BigName\DatabaseBackup\ShellProcessing\ShellProcessor
     */
    protected $shellProcessor;

    public function __construct(FilesystemProvider $filesystemProvider, Config $databaseConfig, ShellProcessor $shellProcessor)
    {
        $this->sequence = new Sequence;
        $this->filesystemProvider = $filesystemProvider;
        $this->databaseConfig = $databaseConfig;
        $this->shellProcessor = $shellProcessor;
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
