<?php namespace BigName\BackupManager\Procedures;

use BigName\BackupManager\Databases\DatabaseProvider;
use BigName\BackupManager\Compressors\CompressorProvider;
use BigName\BackupManager\Filesystems\FilesystemProvider;
use BigName\BackupManager\ShellProcessing\ShellProcessor;

/**
 * Class Procedure
 * @package Procedures
 */
abstract class Procedure
{
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
     * @var \BigName\BackupManager\ShellProcessing\ShellProcessor
     */
    protected $shellProcessor;

    /**
     * @param FilesystemProvider $filesystemProvider
     * @param DatabaseProvider $databaseProvider
     * @param CompressorProvider $compressorProvider
     * @param ShellProcessor $shellProcessor
     * @internal param Sequence $sequence
     */
    public function __construct(FilesystemProvider $filesystemProvider, DatabaseProvider $databaseProvider, CompressorProvider $compressorProvider, ShellProcessor $shellProcessor)
    {
        $this->filesystems = $filesystemProvider;
        $this->databases = $databaseProvider;
        $this->compressors = $compressorProvider;
        $this->shellProcessor = $shellProcessor;
    }

    /**
     * @param $name
     * @param null $filename
     * @throws \BigName\BackupManager\Config\ConfigNotFoundForConnection
     * @return string
     */
    protected function getWorkingFile($name, $filename = null)
    {
        if (is_null($filename)) {
            $filename = uniqid();
        }
        return sprintf('%s/%s', $this->getRootPath($name), $filename);
    }

    /**
     * @param $name
     * @throws \BigName\BackupManager\Config\ConfigFieldNotFound
     * @throws \BigName\BackupManager\Config\ConfigNotFoundForConnection
     * @return string
     */
    protected function getRootPath($name)
    {
        $path = $this->filesystems->getConfig($name, 'root');
        return preg_replace('/\/$/', '', $path);
    }
} 
