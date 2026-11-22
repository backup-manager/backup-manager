<?php namespace BackupManager\Filesystems;

use Google\Cloud\Storage\StorageClient;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\GoogleCloudStorage\GoogleCloudStorageAdapter;
use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter;

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
     * Accepts 'project' or 'projectId'. With Flysystem 2/3 the path prefix is
     * 'prefix' or 'root'; the sample config and the README used the second
     * spelling of each.
     *
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config)
    {
        $v2OrV3 = class_exists(GoogleCloudStorageAdapter::class);

        if (! $v2OrV3 && ! class_exists(GoogleStorageAdapter::class)) {
            throw FilesystemAdapterMissing::forType('Gcs', 'league/flysystem-google-cloud-storage');
        }

        $storageClient = new StorageClient([
            'projectId' => $this->first($config, ['project', 'projectId']),
            'keyFilePath' => $this->first($config, ['keyFilePath']),
        ]);
        $bucket = $storageClient->bucket($config['bucket']);

        if ($v2OrV3) {
            return new Flysystem(new GoogleCloudStorageAdapter($bucket, (string) $this->first($config, ['prefix', 'root'])));
        }

        // Flysystem 1 reads only 'prefix', as 3.1 did, so existing backup paths do not move.
        return new Flysystem(new GoogleStorageAdapter($storageClient, $bucket, $this->first($config, ['prefix'])));
    }

    /**
     * @param array $config
     * @param string[] $keys
     * @return mixed|null the first non-empty value among $keys
     */
    private function first(array $config, array $keys)
    {
        foreach ($keys as $key) {
            if (isset($config[$key]) && $config[$key] !== '') {
                return $config[$key];
            }
        }

        return null;
    }
}
