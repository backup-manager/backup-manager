<?php namespace BackupManager\Tasks\Storage;

use League\Flysystem\FileExistsException;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\FilesystemOperator;
use BackupManager\Tasks\Task;

/**
 * Class TransferFile
 * @package BackupManager\Tasks\Storage
 */
class TransferFile implements Task
{
    /** @var FilesystemInterface|FilesystemOperator */
    private $sourceFilesystem;
    /** @var string */
    private $sourcePath;
    /** @var FilesystemInterface|FilesystemOperator */
    private $destinationFilesystem;
    /** @var string */
    private $destinationPath;

    /**
     * @param FilesystemInterface|FilesystemOperator $sourceFilesystem
     * @param $sourcePath
     * @param FilesystemInterface|FilesystemOperator $destinationFilesystem
     * @param $destinationPath
     */
    public function __construct($sourceFilesystem, $sourcePath, $destinationFilesystem, $destinationPath)
    {
        $this->sourceFilesystem = $sourceFilesystem;
        $this->sourcePath = $sourcePath;
        $this->destinationFilesystem = $destinationFilesystem;
        $this->destinationPath = $destinationPath;
    }

    /**
     * @throws FileExistsException
     * @throws FileNotFoundException
     * @throws FileTransferFailed
     */
    public function execute()
    {
        $stream = $this->sourceFilesystem->readStream($this->sourcePath);

        if ($stream === false) {
            throw new FileTransferFailed(sprintf('Unable to read "%s" from the source filesystem.', $this->sourcePath));
        }

        try {
            $written = $this->destinationFilesystem->writeStream($this->destinationPath, $stream);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        if ($written === false) {
            throw new FileTransferFailed(sprintf('Unable to write "%s" to the destination filesystem.', $this->destinationPath));
        }
    }
}
