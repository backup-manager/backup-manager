<?php namespace BigName\BackupManager\Commands\Storage;

use League\Flysystem\Filesystem;
use BigName\BackupManager\Commands\Command;

/**
 * Class ListDirectoryContents
 * @package BigName\BackupManager\Commands\Storage
 */
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

    /**
     * @param Filesystem $filesystem
     * @param string $path
     */
    public function __construct(Filesystem $filesystem, $path = '')
    {
        $this->filesystem = $filesystem;
        $this->path = $path;
    }

    /**
     * @return array
     */
    public function execute()
    {
        return $this->filesystem->listContents($this->path);
    }
}
