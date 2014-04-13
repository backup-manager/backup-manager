<?php namespace BigName\BackupManager\Commands\Storage;

use League\Flysystem\Filesystem;
use BigName\BackupManager\Commands\Command;

/**
 * Class TransferFile
 * @package BigName\BackupManager\Commands\Storage
 */
class TransferFile implements Command
{
    /**
     * @var \League\Flysystem\Filesystem
     */
    private $sourceFilesystem;
    /**
     * @var string
     */
    private $sourcePath;
    /**
     * @var \League\Flysystem\Filesystem
     */
    private $destinationFilesystem;
    /**
     * @var string
     */
    private $destinationPath;

    /**
     * @param Filesystem $sourceFilesystem
     * @param $sourcePath
     * @param Filesystem $destinationFilesystem
     * @param $destinationPath
     */
    public function __construct(Filesystem $sourceFilesystem, $sourcePath, Filesystem $destinationFilesystem, $destinationPath)
    {
        $this->sourceFilesystem = $sourceFilesystem;
        $this->sourcePath = $sourcePath;
        $this->destinationFilesystem = $destinationFilesystem;
        $this->destinationPath = $destinationPath;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function execute()
    {
        $this->destinationFilesystem->writeStream(
            $this->destinationPath,
            $this->sourceFilesystem->readStream($this->sourcePath)
        );
    }
}
