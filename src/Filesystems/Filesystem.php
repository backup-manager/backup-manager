<?php namespace BigName\DatabaseBackup\Filesystems;
use BigName\DatabaseBackup\Config;

/**
 * Interface Filesystem
 * @package BigName\DatabaseBackup\Filesystems
 */
interface Filesystem
{
    /**
     * Get the name identifier of the filesystem. The name
     * is generally derived from the Flysystem driver name.
     * @return string
     */
    public function handles();

    public function get(array $config);
} 
