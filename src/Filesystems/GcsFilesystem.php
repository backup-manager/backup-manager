<?php namespace BackupManager\Filesystems;

use Google\Cloud\Storage\StorageClient;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\GoogleCloudStorage\GoogleCloudStorageAdapter;

/**
 * Class GcsFilesystem
 * @package BackupManager\Filesystems
 */
class GcsFilesystem implements Filesystem
{

    /**
     * @param $type
     * @return bool
     */
    public function handles($type)
    {
        return strtolower($type ?? '') == 'gcs';
    }

    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config)
    {
        $storageClient = new StorageClient([
            'projectId' => $config['project'],
            'keyFilePath' => isset($config['keyFilePath']) ? $config['keyFilePath'] : null,
        ]);
        $bucket = $storageClient->bucket($config['bucket']);

        return new Flysystem(new GoogleCloudStorageAdapter($storageClient, $bucket, $config['prefix']));
    }
}
