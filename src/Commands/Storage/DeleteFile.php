<?php namespace BigName\BackupManager\Commands\Storage;

use League\Flysystem\Filesystem;
use BigName\BackupManager\Commands\Command;

class DeleteFile implements Command
{
    /**
     * @var \League\Flysystem\Filesystem
     */
    private $filesystem;
    /**
     * @var
     */
    private $filePath;

    public function __construct(Filesystem $filesystem, $filePath)
    {
        $this->filesystem = $filesystem;
        $this->filePath = $filePath;
    }

    public function execute()
    {
        return $this->filesystem->delete($this->filePath);
    }
}
