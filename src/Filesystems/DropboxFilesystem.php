<?php namespace BackupManager\Filesystems;

use League\Flysystem\Dropbox\DropboxAdapter;
use Dropbox\Client;
use League\Flysystem\Filesystem as Flysystem;

/**
 * Class DropboxFilesystem
 * @package BackupManager\Filesystems
 */
class DropboxFilesystem implements Filesystem {

    /**
     * Test fitness of visitor.
     * @param $type
     * @return bool
     */
    public function handles($type) {
        return strtolower($type) == 'dropbox';
    }

    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config) {
        $client = new Client($config['token'], $config['app']);
        return new Flysystem(new DropboxAdapter($client, $config['root']));
    }
}
