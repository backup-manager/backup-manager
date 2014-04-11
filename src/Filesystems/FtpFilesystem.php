<?php namespace BigName\BackupManager\Filesystems; 

use League\Flysystem\Adapter\Ftp;
use League\Flysystem\Filesystem as Flysystem;

class FtpFilesystem implements Filesystem
{
    public function get(array $config)
    {
        return new Flysystem(new Ftp($config));
    }
} 
