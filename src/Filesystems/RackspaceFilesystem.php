<?php namespace BackupManager\Filesystems;

use League\Flysystem\Rackspace\RackspaceAdapter;
use League\Flysystem\Filesystem as Flysystem;
use OpenCloud\OpenStack;

/**
 * Class RackspaceFilesystem
 * @package BackupManager\Filesystems
 */
class RackspaceFilesystem implements Filesystem {

    /**
     * Test fitness of visitor.
     * @param $type
     * @return bool
     */
    public function handles($type) {
        return strtolower($type) == 'rackspace';
    }

    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config) {
        $client = new OpenStack($config['endpoint'], [
            'username' => $config['username'],
            'password' => $config['key'],
        ]);
        $container = $client->objectStoreService('cloudFiles', $config['zone'])->getContainer($config['container']);
        return new Flysystem(new RackspaceAdapter($container, $config['root']));
    }
}
