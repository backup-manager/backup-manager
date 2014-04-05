<?php namespace BigName\DatabaseBackup\Filesystems;

use League\Flysystem\Adapter\Rackspace as RackspaceAdapter;
use OpenCloud\OpenStack;
use OpenCloud\Rackspace;

class RackspaceFilesystem implements Filesystem
{
    public function get(array $config)
    {
        $client = new OpenStack(Rackspace::UK_IDENTITY_ENDPOINT, [
            'username' => $config['username'],
            'password' => $config['password'],
        ]);
        $store = $client->objectStoreService('cloudFiles', 'LON');
        $container = $store->getContainer($config['container']);
        return new \League\Flysystem\Filesystem(new RackspaceAdapter($container));
    }
}
