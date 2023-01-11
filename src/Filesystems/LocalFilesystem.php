<?php namespace BackupManager\Filesystems;

use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\Filesystem as Flysystem;

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
        return new Flysystem(new LocalFilesystemAdapter($config['root']));
    }
}
