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
     * Test fitness of visitor.
     * @param $type
     * @return bool
     */
    public function handles($type)
    {
        return strtolower($type) == 'rackspace';
    }

    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config)
    {
        $client = new OpenStack(Rackspace::UK_IDENTITY_ENDPOINT, [
            'username' => $config['username'],
            'password' => $config['key'],
        ]);
        $container = $client->objectStoreService('cloudFiles', $config['zone'])->getContainer($config['container']);
        return new Flysystem(new RackspaceAdapter($container, $config['root']));
    }
}
