<?php namespace BackupManager\Tasks\Storage;

use League\Flysystem\FileNotFoundException;
use League\Flysystem\Filesystem;
use BackupManager\Tasks\Task;
use League\Flysystem\FilesystemException;

/**
 * Class DeleteFile
 * @package BackupManager\Tasks\Storage
 */
class DeleteFile implements Task
{
    /** @var Filesystem */
    private $filesystem;
    /** @var string */
    private $filePath;

    /**
     * @param Filesystem $filesystem
     * @param $filePath
     */
    public function __construct(Filesystem $filesystem, $filePath)
    {
        $this->filesystem = $filesystem;
        $this->filePath = $filePath;
    }

    /**
     * @return bool
     * @throws FilesystemException
     */
    public function execute()
    {
        $this->filesystem->delete($this->filePath);

        return true;
    }
}
