<?php namespace BigName\BackupManager\Procedures;

use BigName\BackupManager\Commands\Command;
use BigName\BackupManager\Compressors\CompressorProvider;
use BigName\BackupManager\Databases\DatabaseProvider;
use BigName\BackupManager\Filesystems\FilesystemProvider;
use BigName\BackupManager\ShellProcessing\ShellProcessor;

/**
 * Class Procedure
 * @package Procedures
 */
abstract class Procedure
{
    private $sequence;
    /**
     * @var \BigName\BackupManager\Filesystems\FilesystemProvider
     */
    protected $filesystem;
    /**
     * @var \BigName\BackupManager\ShellProcessing\ShellProcessor
     */
    protected $shellProcessor;
    /**
     * @var \BigName\BackupManager\Databases\DatabaseProvider
     */
    protected $database;
    /**
     * @var \BigName\BackupManager\Compressors\CompressorProvider
     */
    public $compressor;

    public function __construct(FilesystemProvider $filesystemProvider, DatabaseProvider $databaseProvider, CompressorProvider $compressorProvider, ShellProcessor $shellProcessor, Sequence $sequence)
    {
        $this->filesystem = $filesystemProvider;
        $this->database = $databaseProvider;
        $this->compressor = $compressorProvider;
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
