<?php namespace BackupManager\Filesystems;

use BackblazeB2\Client;
use League\Flysystem\Filesystem as Flysystem;
use Mhetreramesh\Flysystem\BackblazeAdapter;

/**
 * Class BackblazeFilesystem
 * @package BackupManager\Filesystems
 */
class BackblazeFilesystem implements Filesystem {

    /**
     * Test fitness of visitor.
     * @param $type
     * @return bool
     */
    public function handles($type) {
        return strtolower($type) == 'b2';
    }

    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config) {
        if (!isset($config['options'])) {
            $config['options'] = [];
        }

        $client = new Client($config['accountId'], $config['key'], $config['options']);
        return new Flysystem(new BackblazeAdapter($client, $config['bucket']));
    }
}