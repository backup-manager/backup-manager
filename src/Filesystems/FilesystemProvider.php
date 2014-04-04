<?php namespace BigName\DatabaseBackup\Filesystems;

use BigName\DatabaseBackup\Config\Config;

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
        if ( ! class_exists($class)) {
            throw new FilesystemTypeNotSupported('The requested filesystem type "' . $class . '" is not currently supported.');
        }
        $filesystem = new $class;
        return $filesystem->get($this->config->get($name));
    }
}
