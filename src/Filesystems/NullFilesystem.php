<?php namespace BigName\BackupManager\Filesystems;

use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\Adapter\NullAdapter;

/**
 * Class NullFilesystem
 * @package BigName\BackupManager\Filesystems
 */
class NullFilesystem implements Filesystem
{
    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config)
    {
        return new Flysystem(new NullAdapter());
    }
}
