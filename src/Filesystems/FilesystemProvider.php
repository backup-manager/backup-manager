<?php namespace BigName\BackupManager\Filesystems;

use BigName\BackupManager\Config\Config;

/**
 * Class FilesystemProvider
 * @package BigName\BackupManager\Filesystems
 */
class FilesystemProvider
{
    /**
     * @var \BigName\BackupManager\Config\Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param $name
     * @return \League\Flysystem\Filesystem
     * @throws FilesystemTypeNotSupported
     * @throws \BigName\BackupManager\Config\ConfigNotFoundForConnection
     */
    public function get($name)
    {
        $type = $this->config->get($name, 'type');
        if (is_null($type)) {
            return (new NullFilesystem())->get([]);
        }
        $class = $this->getClassName($type);
        if ( ! class_exists($class)) {
            throw new FilesystemTypeNotSupported('The requested filesystem type "' . $class . '" is not currently supported.');
        }
        return (new $class)->get($this->config->get($name));
    }

    /**
     * @param $type
     * @return string
     */
    private function getClassName($type)
    {
        $type = ucfirst(strtolower($type));
        return "BigName\\BackupManager\\Filesystems\\{$type}Filesystem";
    }

    /**
     * @return array
     */
    public function getAvailableProviders()
    {
        return array_keys($this->config->getItems());
    }
}
