<?php namespace BackupManager\Procedures;

use BackupManager\Databases\DatabaseProvider;
use BackupManager\Compressors\CompressorProvider;
use BackupManager\Filesystems\FilesystemProvider;
use BackupManager\ShellProcessing\ShellProcessor;

/**
 * Class Procedure
 * @package Procedures
 */
abstract class Procedure {

    /** @var FilesystemProvider */
    protected $filesystems;
    /** @var DatabaseProvider */
    protected $databases;
    /** @var CompressorProvider */
    protected $compressors;
    /** @var ShellProcessor */
    protected $shellProcessor;

    /**
     * @param FilesystemProvider $filesystemProvider
     * @param DatabaseProvider $databaseProvider
     * @param CompressorProvider $compressorProvider
     * @param ShellProcessor $shellProcessor
     * @internal param Sequence $sequence
     */
    public function __construct(FilesystemProvider $filesystemProvider, DatabaseProvider $databaseProvider, CompressorProvider $compressorProvider, ShellProcessor $shellProcessor) {
        $this->filesystems = $filesystemProvider;
        $this->databases = $databaseProvider;
        $this->compressors = $compressorProvider;
        $this->shellProcessor = $shellProcessor;
    }

    /**
     * @param $name
     * @param null $filename
     * @throws \BackupManager\Config\ConfigNotFoundForConnection
     * @return string
     */
    protected function getWorkingFile($name, $filename = null) {
        if (is_null($filename)) {
            $filename = uniqid();
        }
        return sprintf('%s/%s', $this->getRootPath($name), $filename);
    }

    /**
     * @param $name
     * @throws \BackupManager\Config\ConfigFieldNotFound
     * @throws \BackupManager\Config\ConfigNotFoundForConnection
     * @return string
     */
    protected function getRootPath($name) {
        $path = $this->filesystems->getConfig($name, 'root');
        return preg_replace('/\/$/', '', $path);
    }
} 
