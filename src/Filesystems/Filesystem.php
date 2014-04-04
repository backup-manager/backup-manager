<?php namespace BigName\DatabaseBackup\Filesystems;

/**
 * Interface Filesystem
 * @package BigName\DatabaseBackup\Filesystems
 */
interface Filesystem
{
    /**
     * Get the name identifier of the filesystem. The name
     * is generally derived from the Flysystem driver name.
     * @param string $type
     * @return string
     */
    public function handles($type);

    public function get(array $config);
} 
