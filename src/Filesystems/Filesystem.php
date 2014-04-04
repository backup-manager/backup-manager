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
     * @param string $name
     * @return string
     */
    public function handles($name);

    public function get(array $config);
} 
