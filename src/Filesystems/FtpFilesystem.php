<?php namespace BigName\BackupManager\Filesystems; 

use League\Flysystem\Adapter\Ftp;
use League\Flysystem\Filesystem as Flysystem;

/**
 * Class FtpFilesystem
 * @package BigName\BackupManager\Filesystems
 */
class FtpFilesystem implements Filesystem
{
    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config)
    {
        return new Flysystem(new Ftp($config));
    }
} 
