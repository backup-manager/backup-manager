<?php namespace BackupManager\Filesystems;

use Google\Cloud\Storage\StorageClient;
use League\Flysystem\Filesystem as Flysystem;
use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter;

/**
 * Class GcsFilesystem
 * @package BackupManager\Filesystems
 */
class GcsFilesystem implements Filesystem {

    /**
     * @param $type
     * @return bool
     */
    public function handles($type) {
        return strtolower($type) == 'gcs';
    }

    /**
     * @param array $config
     * @return \League\Flysystem\Filesystem
     */
    public function get(array $config) {

        $storageClient = new StorageClient([
            'projectId' => $config['project'],
        ]);
        $bucket = $storageClient->bucket($config['bucket']);

        return new Flysystem(new GoogleStorageAdapter($storageClient, $bucket, $config['prefix']));
    }
}
