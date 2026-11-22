<?php namespace BackupManager\Filesystems;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\Local\LocalFilesystemAdapter;

/**
 * Class LocalFilesystem
 * @package BackupManager\Filesystems
 */
class LocalFilesystem implements Filesystem
{
    /**
     * Test fitness of visitor.
     * @param $type
     * @return bool
     */
    public function handles($type)
    {
        return strtolower($type ?? '') == 'local';
    }

    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config)
    {
        // Flysystem v2/v3
        if (class_exists(LocalFilesystemAdapter::class)) {
            return new Flysystem(new LocalFilesystemAdapter($config['root']));
        }

        // Flysystem v1
        return new Flysystem(new Local($config['root']));
    }
}
