<?php namespace BackupManager\Filesystems;

use League\Flysystem\Filesystem as Flysystem;
use Srmklive\Dropbox\Client\DropboxClient;
use Srmklive\Dropbox\Adapter\DropboxAdapter;

/**
 * @package BackupManager\Filesystems
 * @deprecated since 3.2.0, removed in 4.0: its adapter package only supports Flysystem 1. Use DropboxFilesystem (spatie/flysystem-dropbox).
 */
class DropboxV2Filesystem implements Filesystem
{
    /**
     * Test fitness of visitor.
     * @param $type
     * @return bool
     */
    public function handles($type)
    {
        return strtolower($type ?? '') == 'dropboxv2';
    }

    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config)
    {
        if (! class_exists(DropboxAdapter::class)) {
            throw FilesystemAdapterMissing::forFlysystemV1Type('DropboxV2', 'srmklive/flysystem-dropbox-v2', 'Use the "Dropbox" type with spatie/flysystem-dropbox instead.');
        }

        $client = new DropboxClient($config['token']);
        return new Flysystem(new DropboxAdapter($client, $config['root']));
    }
}
