<?php namespace BigName\BackupManager\Filesystems;

class LocalFilesystem implements Filesystem
{
    public function get(array $config)
    {
        return new \League\Flysystem\Filesystem(new \League\Flysystem\Adapter\Local($config['working-path']));
    }
}
