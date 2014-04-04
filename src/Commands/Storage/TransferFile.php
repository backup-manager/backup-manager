<?php namespace BigName\DatabaseBackup\Commands\Storage;

use League\Flysystem\Filesystem;
use BigName\DatabaseBackup\Commands\Command;

class TransferFile implements Command
{
    /**
     * @var \League\Flysystem\Filesystem
     */
    private $sourceFilesystem;
    /**
     * @var
     */
    private $sourcePath;
    /**
     * @var \League\Flysystem\Filesystem
     */
    private $destinationFilesystem;
    /**
     * @var
     */
    private $destinationPath;

    public function __construct(Filesystem $sourceFilesystem, $sourcePath, Filesystem $destinationFilesystem, $destinationPath)
    {
        $this->sourceFilesystem = $sourceFilesystem;
        $this->sourcePath = $sourcePath;
        $this->destinationFilesystem = $destinationFilesystem;
        $this->destinationPath = $destinationPath;
    }

    public function execute()
    {
        $this->destinationFilesystem->writeStream(
            $this->destinationPath,
            $this->sourceFilesystem->readStream($this->sourcePath)
        );
    }
}
