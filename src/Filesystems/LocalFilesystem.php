<?php namespace BigName\DatabaseBackup\Filesystems;

/**
 * Class LocalFilesystem
 * @package BigName\DatabaseBackup\Filesystems
 */
class LocalFilesystem implements Filesystem
{
    /**
     * Get the name identifier of the filesystem. The name
     * is generally derived from the Flysystem driver name.
     * @param string $name
     * @return string
     */
    public function handles($name)
    {
        return $name == 'Local';
    }

    public function get(array $config)
    {
        return new \League\Flysystem\Filesystem(new \League\Flysystem\Adapter\Local(realpath($config['root-path'])));
    }
}
