<?php namespace BigName\DatabaseBackup\Procedures;

use BigName\DatabaseBackup\Commands\Command;
use BigName\DatabaseBackup\Commands\CommandFactory;
use BigName\DatabaseBackup\Config\Config;
use BigName\DatabaseBackup\Databases\DatabaseProvider;
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
     * @var \BigName\DatabaseBackup\ShellProcessing\ShellProcessor
     */
    protected $shellProcessor;
    /**
     * @var \BigName\DatabaseBackup\Databases\DatabaseProvider
     */
    protected $databaseProvider;

    public function __construct(FilesystemProvider $filesystemProvider, DatabaseProvider $databaseProvider, ShellProcessor $shellProcessor, Sequence $sequence)
    {
        $this->filesystemProvider = $filesystemProvider;
        $this->databaseProvider = $databaseProvider;
        $this->shellProcessor = $shellProcessor;
        $this->sequence = $sequence;
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
