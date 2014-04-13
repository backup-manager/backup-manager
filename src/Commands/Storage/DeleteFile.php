<?php namespace BigName\BackupManager\Commands\Storage;

use League\Flysystem\Filesystem;
use BigName\BackupManager\Commands\Command;

/**
 * Class DeleteFile
 * @package BigName\BackupManager\Commands\Storage
 */
class DeleteFile implements Command
{
    /**
     * @var \League\Flysystem\Filesystem
     */
    private $filesystem;
    /**
     * @var string
     */
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
     */
    public function execute()
    {
        return $this->filesystem->delete($this->filePath);
    }
}
