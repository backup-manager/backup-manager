<?php namespace BigName\BackupManager\Filesystems;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem as Flysystem;

/**
 * Class LocalFilesystem
 * @package BigName\BackupManager\Filesystems
 */
class LocalFilesystem implements Filesystem
{
    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config)
    {
        return new Flysystem(new Local($config['working-path']));
    }
}
