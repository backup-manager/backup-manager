<?php namespace BigName\DatabaseBackup\Filesystems; 

class NullFilesystem implements Filesystem
{
    public function get(array $config)
    {
        return new \League\Flysystem\Filesystem(new \League\Flysystem\Adapter\NullAdapter());
    }
}
