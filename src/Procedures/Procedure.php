<?php namespace BigName\DatabaseBackup\Procedures;

use BigName\DatabaseBackup\Commands\Command;
use BigName\DatabaseBackup\Compressors\CompressorProvider;
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
    protected $filesystem;
    /**
     * @var \BigName\DatabaseBackup\ShellProcessing\ShellProcessor
     */
    protected $shellProcessor;
    /**
     * @var \BigName\DatabaseBackup\Databases\DatabaseProvider
     */
    protected $database;
    /**
     * @var \BigName\DatabaseBackup\Compressors\CompressorProvider
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
