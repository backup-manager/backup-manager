<?php namespace BigName\BackupManager\Filesystems;

use League\Flysystem\Adapter\Dropbox;
use Dropbox\Client;
use League\Flysystem\Filesystem as Flysystem;

/**
 * Class DropboxFilesystem
 * @package BigName\BackupManager\Filesystems
 */
class DropboxFilesystem implements Filesystem
{
    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config)
    {
        $client = new Client($config['token'], $config['app']);
        return new Flysystem(new Dropbox($client, $config['root']));
    }
}
