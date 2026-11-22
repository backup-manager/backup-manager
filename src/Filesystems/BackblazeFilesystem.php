<?php namespace BackupManager\Filesystems;

use BackblazeB2\Client;
use League\Flysystem\Filesystem as Flysystem;
use Mhetreramesh\Flysystem\BackblazeAdapter;

/**
 * Class BackblazeFilesystem
 * @package BackupManager\Filesystems
 * @deprecated since 3.2.0, removed in 4.0: its adapter package only supports Flysystem 1. Use Awss3Filesystem with the B2 S3-compatible endpoint.
 */
class BackblazeFilesystem implements Filesystem
{
    /**
     * Test fitness of visitor.
     * @param $type
     * @return bool
     */
    public function handles($type)
    {
        return strtolower($type ?? '') == 'b2';
    }

    /**
     * @param array $config
     * @return Flysystem
     * @throws \Exception
     */
    public function get(array $config)
    {
        if (! class_exists(BackblazeAdapter::class)) {
            throw FilesystemAdapterMissing::forFlysystemV1Type('B2', 'mhetreramesh/flysystem-backblaze', 'Use the "AwsS3" type with the B2 S3-compatible endpoint instead.');
        }

        if (!isset($config['options'])) {
            $config['options'] = [];
        }

        $client = new Client($config['accountId'], $config['key'], $config['options']);
        return new Flysystem(new BackblazeAdapter($client, $config['bucket']));
    }
}
