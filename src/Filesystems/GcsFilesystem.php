<?php

declare(strict_types=1);

namespace BackupManager\Filesystems;

use Google\Cloud\Storage\StorageClient;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\GoogleCloudStorage\GoogleCloudStorageAdapter;

final class GcsFilesystem implements Filesystem
{
    #[\Override]
    public function handles(?string $type): bool
    {
        return strtolower($type ?? '') === 'gcs';
    }

    /**
     * Accepts 'projectId' or 'project', and 'root' or 'prefix'.
     */
    #[\Override]
    public function get(array $config): FilesystemOperator
    {
        if (!class_exists(GoogleCloudStorageAdapter::class)) {
            throw FilesystemAdapterMissing::forType('Gcs', 'league/flysystem-google-cloud-storage');
        }

        $storageClient = new StorageClient(array_filter([
            'projectId' => $this->first($config, ['projectId', 'project']),
            'keyFilePath' => $this->first($config, ['keyFilePath']),
        ]));

        return new Flysystem(new GoogleCloudStorageAdapter(
            $storageClient->bucket($config['bucket']),
            (string) $this->first($config, ['root', 'prefix'])
        ));
    }

    /**
     * @param array<string, mixed> $config
     * @param list<string> $keys
     */
    private function first(array $config, array $keys): mixed
    {
        foreach ($keys as $key) {
            if (isset($config[$key]) && $config[$key] !== '') {
                return $config[$key];
            }
        }

        return null;
    }
}
