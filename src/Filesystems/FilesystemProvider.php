<?php namespace BigName\DatabaseBackup\Filesystems;

use BigName\DatabaseBackup\Config;

class FilesystemProvider
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getType($name)
    {
        $class = __NAMESPACE__ . "\\" . $this->config->get($name, 'type') . 'Filesystem';
        $filesystem = new $class;
        return $filesystem->get($this->config->get($name));
    }
}
