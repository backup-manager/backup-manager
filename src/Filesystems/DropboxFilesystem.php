<?php namespace BackupManager\Filesystems;

use League\Flysystem\Filesystem as Flysystem;
use Srmklive\Dropbox\Client\DropboxClient;
use Srmklive\Dropbox\Adapter\DropboxAdapter;

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
        $client = new DropboxClient($config['token']);
        return new Flysystem(new DropboxAdapter($client, $config['root']));
    }
}
