<?php namespace BackupManager\Filesystems;

use AsyncAws\Flysystem\S3\S3FilesystemV1;
use AsyncAws\S3\S3Client;
use League\Flysystem\Filesystem as Flysystem;

/**
 * @package BackupManager\Filesystems
 */
class AsyncAwsS3Filesystem implements Filesystem
{
    /**
     * @param $type
     * @return bool
     */
    public function handles($type)
    {
        return strtolower($type) == 'asyncawss3';
    }

    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config)
    {
        $clientConfig = [
            'pathStyleEndpoint' => isset($config['use_path_style_endpoint']) ? $config['use_path_style_endpoint'] : false
        ];

        if (null !== $config['key']) {
            $clientConfig['accessKeyId'] = $config['key'];
        }

        if (null !== $config['secret']) {
            $clientConfig['accessKeySecret'] = $config['secret'];
        }

        if (null !== $config['region']) {
            $clientConfig['region'] = $config['region'];
        }

        if (null !== $config['endpoint']) {
            $clientConfig['endpoint'] = $config['endpoint'];
        }

        return new Flysystem(new S3FilesystemV1(new S3Client($clientConfig), $config['bucket'], $config['root']));
    }
}
