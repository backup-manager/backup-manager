<?php namespace BackupManager\Filesystems;

use League\Flysystem\Ftp\FtpAdapter;
use League\Flysystem\Ftp\FtpConnectionOptions;
use League\Flysystem\Filesystem as Flysystem;

/**
 * Class FtpFilesystem
 * @package BackupManager\Filesystems
 */
class FtpFilesystem implements Filesystem
{
    /**
     * Test fitness of visitor.
     * @param $type
     * @return bool
     */
    public function handles($type)
    {
        return strtolower($type ?? '') == 'ftp';
    }

    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config)
    {
        return new Flysystem(new FtpAdapter(new FtpConnectionOptions(...($config["connection"] ?? []))));
    }
}
