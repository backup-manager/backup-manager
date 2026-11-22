<?php namespace BackupManager\Filesystems;

use AsyncAws\S3\S3Client;
use League\Flysystem\AsyncAwsS3\AsyncAwsS3Adapter;
use League\Flysystem\Filesystem as Flysystem;

/**
 * S3 storage through AsyncAws, a lighter alternative to the official AWS SDK.
 * Requires Flysystem v2 or v3 and league/flysystem-async-aws-s3.
 *
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
        return in_array(strtolower($type ?? ''), ['asyncawss3', 'async-awss3', 'async-aws-s3'], true);
    }

    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config)
    {
        if (! class_exists(AsyncAwsS3Adapter::class)) {
            throw FilesystemAdapterMissing::forType('AsyncAwsS3', 'league/flysystem-async-aws-s3');
        }

        // AsyncAws configuration values are strings.
        $clientConfig = [
            'pathStyleEndpoint' => ! empty($config['use_path_style_endpoint']) ? 'true' : 'false',
        ];

        $options = ['key' => 'accessKeyId', 'secret' => 'accessKeySecret', 'region' => 'region', 'endpoint' => 'endpoint'];
        foreach ($options as $key => $option) {
            if (isset($config[$key]) && $config[$key] !== '') {
                $clientConfig[$option] = (string) $config[$key];
            }
        }

        return new Flysystem(new AsyncAwsS3Adapter(
            new S3Client($clientConfig),
            $config['bucket'],
            isset($config['root']) ? $config['root'] : ''
        ));
    }
}
