<?php namespace BackupManager\Filesystems;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Filesystem as Flysystem;

/**
 * Class Awss3Filesystem
 * @package BackupManager\Filesystems
 */
class Awss3Filesystem implements Filesystem
{
    /**
     * @param $type
     * @return bool
     */
    public function handles($type)
    {
        return strtolower($type ?? '') == 'awss3';
    }

    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config)
    {
        $v2OrV3 = class_exists(AwsS3V3Adapter::class);

        if (! $v2OrV3 && ! class_exists(AwsS3Adapter::class)) {
            throw FilesystemAdapterMissing::forType('AwsS3', 'league/flysystem-aws-s3-v3');
        }

        if (! class_exists(S3Client::class)) {
            throw FilesystemAdapterMissing::forType('AwsS3', 'aws/aws-sdk-php');
        }

        $client = new S3Client([
            'credentials' => [
                'key' => $config['key'],
                'secret' => $config['secret'],
            ],
            'region' => $config['region'],
            'version' => isset($config['version']) ? $config['version'] : 'latest',
            'endpoint' => isset($config['endpoint']) ? $config['endpoint'] : null,
            'use_path_style_endpoint' => isset($config['use_path_style_endpoint']) ? $config['use_path_style_endpoint'] : false,
        ]);

        $root = isset($config['root']) ? $config['root'] : '';

        if ($v2OrV3) {
            return new Flysystem(new AwsS3V3Adapter($client, $config['bucket'], $root));
        }

        return new Flysystem(new AwsS3Adapter($client, $config['bucket'], $root));
    }
}
