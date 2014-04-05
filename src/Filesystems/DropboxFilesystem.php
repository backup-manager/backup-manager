<?php namespace BigName\DatabaseBackup\Filesystems;

use League\Flysystem\Adapter\Dropbox;
use Dropbox\Client;

class DropboxFilesystem implements Filesystem
{
    public function get(array $config)
    {
        $client = new Client($config['token'], $config['app']);
        return new \League\Flysystem\Filesystem(new Dropbox($client, $config['root']));
    }
}
