<?php namespace BigName\BackupManager;

use BigName\BackupManager\Compressors\GzipCompressor;
use BigName\BackupManager\Config\Config;
use BigName\BackupManager\Databases\DatabaseProvider;
use BigName\BackupManager\Databases\MysqlDatabase;
use BigName\BackupManager\Databases\PostgresqlDatabase;
use BigName\BackupManager\Filesystems;
use BigName\BackupManager\Compressors\CompressorProvider;
use BigName\BackupManager\Procedures\Sequence;
use BigName\BackupManager\Procedures;
use BigName\BackupManager\ShellProcessing\ShellProcessor;
use Symfony\Component\Process\Process;

/**
 * Class Manager
 * @package BigName\BackupManager
 */
class Manager
{
    /**
     * @var Config\Config
     */
    protected $storage;
    /**
     * @var Config\Config
     */
    protected $database;
    /**
     * @var \BigName\BackupManager\Filesystems\FilesystemProvider
     */
    protected $filesystems;
    /**
     * @var \BigName\BackupManager\Databases\DatabaseProvider
     */
    protected $databases;
    /**
     * @var \BigName\BackupManager\Compressors\CompressorProvider
     */
    protected $compressors;

    /**
     * @param $storage
     * @param $database
     */
    public function __construct($storage, $database)
    {

        $this->storage = new Config($storage);
        $this->database = new Config($database);
    }

    /**
     * @return BackupProcedure
     */
    public function makeBackup()
    {
        return new Procedures\BackupProcedure(
            $this->getFilesystemProvider(),
            $this->getDatabaseProvider(),
            $this->getCompressorProvider(),
            $this->getShellProcessor(),
            new Sequence
        );
    }

    /**
     * @return RestoreProcedure
     */
    public function makeRestore()
    {
        return new Procedures\RestoreProcedure(
            $this->getFilesystemProvider(),
            $this->getDatabaseProvider(),
            $this->getCompressorProvider(),
            $this->getShellProcessor(),
            new Sequence
        );
    }

    /**
     * @return ShellProcessor
     */
    protected function getShellProcessor()
    {
        return new ShellProcessor(new Process(''));
    }

    /**
     * @return FilesystemProvider
     */
    protected function getFilesystemProvider()
    {
        if ( ! $this->filesystems) {
            $provider = new Filesystems\FilesystemProvider($this->storage);
            $provider->add(new Filesystems\Awss3Filesystem);
            $provider->add(new Filesystems\DropboxFilesystem);
            $provider->add(new Filesystems\FtpFilesystem);
            $provider->add(new Filesystems\LocalFilesystem);
            $provider->add(new Filesystems\RackspaceFilesystem);
            $provider->add(new Filesystems\SftpFilesystem);
            $this->filesystems = $provider;
        }
        return $this->filesystems;
    }

    /**
     * @return CompressorProvider
     */
    protected function getCompressorProvider()
    {
        if ( ! $this->compressors) {
            $provider = new CompressorProvider;
            $provider->add(new GzipCompressor);
            $this->compressors = $provider;
        }
        return $this->compressors;
    }

    /**
     * @return DatabaseProvider
     */
    protected function getDatabaseProvider()
    {
        if ( ! $this->databases) {
            $provider = new DatabaseProvider($this->database);
            $provider->add(new MysqlDatabase);
            $provider->add(new PostgresqlDatabase);
            $this->databases = $provider;
        }
        return $this->databases;
    }
} 
