<?php namespace BigName\BackupManager\Filesystems;

/**
 * Interface Filesystem
 * @package BigName\BackupManager\Filesystems
 */
interface Filesystem
{
    /**
     * @param array $config
     * @return \League\Flysystem\Filesystem
     */
    public function get(array $config);
} 
