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
        $filesystem = new $this->getClassName($this->config->get($name, 'type'));
        return $filesystem->get($this->config->get($name));
    }

    private function getClassName($type)
    {
        return "BigName\\DatabaseBackup\\Filesystems\\{$type}Filesystem";
    }
}
