<?php namespace BackupManager\Tasks\Storage;

use League\Flysystem\Filesystem;
use BackupManager\Tasks\Task;

/**
 * Class TransferFile
 * @package BackupManager\Tasks\Storage
 */
class TransferFile implements Task {

    /** @var Filesystem */
    private $sourceFilesystem;
    /** @var string */
    private $sourcePath;
    /** @var Filesystem */
    private $destinationFilesystem;
    /** @var string */
    private $destinationPath;

    /**
     * @param Filesystem $sourceFilesystem
     * @param $sourcePath
     * @param Filesystem $destinationFilesystem
     * @param $destinationPath
     */
    public function __construct(Filesystem $sourceFilesystem, $sourcePath, Filesystem $destinationFilesystem, $destinationPath) {
        $this->sourceFilesystem = $sourceFilesystem;
        $this->sourcePath = $sourcePath;
        $this->destinationFilesystem = $destinationFilesystem;
        $this->destinationPath = $destinationPath;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function execute() {
        $this->destinationFilesystem->writeStream(
            $this->destinationPath,
            $this->sourceFilesystem->readStream($this->sourcePath)
        );
    }
}
