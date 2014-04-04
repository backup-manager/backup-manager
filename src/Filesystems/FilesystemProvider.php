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
        foreach ($this->filesystems as $filesystem) {
            if ($filesystem->handles($this->config->get($name, 'type'))) {
                return $filesystem->get($this->config->get($name));
            }
        }
    }
}
