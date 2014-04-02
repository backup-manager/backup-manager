<?php namespace McCool\DatabaseBackup\Commands\Storage;

use League\Flysystem\Filesystem;
use McCool\DatabaseBackup\Commands\Command;

class SaveFile implements Command
{
    /**
     * @var \League\Flysystem\Filesystem
     */
    private $filesystem;
    /**
     * @var
     */
    private $sourcePath;
    /**
     * @var
     */
    private $destinationPath;

    public function __construct(Filesystem $filesystem, $sourcePath, $destinationPath)
    {
        $this->filesystem = $filesystem;
        $this->sourcePath = $sourcePath;
        $this->destinationPath = $destinationPath;
    }

    public function execute()
    {
        $stream = fopen($this->sourcePath, 'r+');
        return $this->filesystem->writeStream($this->destinationPath, $stream);
    }
}
