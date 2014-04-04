<?php namespace BigName\DatabaseBackup\Filesystems;

use BigName\DatabaseBackup\Config;

class FilesystemProvider
{
    private $config;
    private $filesystems = [];

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function AddFilesystem(Filesystem $filesystem)
    {
        $this->filesystems[] = $filesystem;
    }

    public function getForConnection($name)
    {
        $filesystemType = $this->config->get($name, 'type');

        foreach ($this->filesystems as $filesystem) {
            if ($filesystem->handles($filesystemType)) {
                return $filesystem->get($this->config->get($name));
            }
        }

        throw new FilesystemNotSupported('The filesystem ' . $filesystemType . ' is not supported.');
    }
}
