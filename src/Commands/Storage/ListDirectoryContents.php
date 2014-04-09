<?php namespace BigName\BackupManager\Commands\Storage;

use League\Flysystem\Filesystem;
use BigName\BackupManager\Commands\Command;

class ListDirectoryContents implements Command
{
    /**
     * @var \League\Flysystem\Filesystem
     */
    private $filesystem;
    /**
     * @var string
     */
    private $path;

    public function __construct(Filesystem $filesystem, $path = '')
    {
        $this->filesystem = $filesystem;
        $this->path = $path;
    }

    public function execute()
    {
        return $this->filesystem->listContents($this->path);
    }
}
