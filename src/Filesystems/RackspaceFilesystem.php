<?php namespace BigName\BackupManager\Filesystems;

use League\Flysystem\Adapter\Rackspace as RackspaceAdapter;
use League\Flysystem\Filesystem as Flysystem;
use OpenCloud\OpenStack;
use OpenCloud\Rackspace;

/**
 * Class RackspaceFilesystem
 * @package BigName\BackupManager\Filesystems
 */
class RackspaceFilesystem implements Filesystem
{
    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config)
    {
        $client = new OpenStack(Rackspace::UK_IDENTITY_ENDPOINT, [
            'username' => $config['username'],
            'password' => $config['password'],
        ]);
        return new Flysystem(new RackspaceAdapter($client->objectStoreService('cloudFiles', 'LON')->getContainer($config['container'])));
    }
}
