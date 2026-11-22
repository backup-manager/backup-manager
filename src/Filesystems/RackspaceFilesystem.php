<?php namespace BackupManager\Filesystems;

use League\Flysystem\Rackspace\RackspaceAdapter;
use League\Flysystem\Filesystem as Flysystem;
use OpenCloud\Rackspace;

/**
 * Class RackspaceFilesystem
 * @package BackupManager\Filesystems
 * @deprecated since 3.2.0, removed in 4.0: its adapter package only supports Flysystem 1. There is no Flysystem 2 or 3 adapter for Rackspace Cloud Files.
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
        return strtolower($type ?? '') == 'rackspace';
    }

    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config)
    {
        if (! class_exists(RackspaceAdapter::class)) {
            throw FilesystemAdapterMissing::forFlysystemV1Type('Rackspace', 'league/flysystem-rackspace', 'There is no Flysystem 2 or 3 adapter for Rackspace Cloud Files.');
        }

        $client = new Rackspace($config['endpoint'], [
            'username' => $config['username'],
            'apiKey' => $config['key'],
        ]);
        $container = $client->objectStoreService('cloudFiles', $config['zone'])->getContainer($config['container']);
        return new Flysystem(new RackspaceAdapter($container, $config['root']));
    }
}
