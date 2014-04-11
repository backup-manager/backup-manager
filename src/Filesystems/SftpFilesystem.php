<?php namespace BigName\BackupManager\Filesystems;

use League\Flysystem\Adapter\Sftp;
use League\Flysystem\Filesystem as Flysystem;

class SftpFilesystem implements Filesystem
{
    public function get(array $config)
    {
        return new Flysystem(new Sftp($config));
    }
} 
