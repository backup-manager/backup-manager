<?php namespace BackupManager\Filesystems;

use BackupManager\Config\Config;

/**
 * Class FilesystemProvider
 * @package BackupManager\Filesystems
 */
class FilesystemProvider {

    /** @var Config */
    private $config;
    /** @var array */
    private $filesystems = [];

    /**
     * @param Config $config
     */
    public function __construct(Config $config) {
        $this->config = $config;
    }

    /**
     * @param Filesystem $filesystem
     */
    public function add(Filesystem $filesystem) {
        $this->filesystems[] = $filesystem;
    }

    /**
     * @param $name
     * @return \League\Flysystem\Filesystem
     * @throws FilesystemTypeNotSupported
     * @throws \BackupManager\Config\ConfigNotFoundForConnection
     */
    public function get($name) {
        $type = $this->getConfig($name, 'type');
        foreach ($this->filesystems as $filesystem) {
            if ($filesystem->handles($type)) {
                return $filesystem->get($this->config->get($name));
            }
        }
        throw new FilesystemTypeNotSupported("The requested filesystem type {$type} is not currently supported.");
    }

    /**
     * @param $name
     * @param null $key
     * @throws \BackupManager\Config\ConfigFieldNotFound
     * @throws \BackupManager\Config\ConfigNotFoundForConnection
     * @return mixed
     */
    public function getConfig($name, $key = null) {
        return $this->config->get($name, $key);
    }

    /**
     * @return array
     */
    public function getAvailableProviders() {
        return array_keys($this->config->getItems());
    }
}
