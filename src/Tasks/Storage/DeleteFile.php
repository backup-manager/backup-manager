<?php namespace BackupManager\Tasks\Storage;

use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\FilesystemOperator;
use BackupManager\Tasks\Task;

/**
 * Class DeleteFile
 * @package BackupManager\Tasks\Storage
 */
class DeleteFile implements Task
{
    /** @var FilesystemInterface|FilesystemOperator */
    private $filesystem;
    /** @var string */
    private $filePath;

    /**
     * @param FilesystemInterface|FilesystemOperator $filesystem
     * @param $filePath
     */
    public function __construct($filesystem, $filePath)
    {
        $this->filesystem = $filesystem;
        $this->filePath = $filePath;
    }

    /**
     * @return bool false only when Flysystem v1 reports a failed delete
     * @throws FileNotFoundException
     */
    public function execute()
    {
        $result = $this->filesystem->delete($this->filePath);

        // Flysystem v2/v3 return void and throw on failure.
        return $result === null ? true : $result;
    }
}
