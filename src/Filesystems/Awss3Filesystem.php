<?php namespace BigName\BackupManager\Filesystems;

use League\Flysystem\Adapter\AwsS3;
use Aws\S3\S3Client;
use League\Flysystem\Filesystem as Flysystem;

/**
 * Class Awss3Filesystem
 * @package BigName\BackupManager\Filesystems
 */
class Awss3Filesystem implements Filesystem
{
    /**
     * @param $type
     * @return bool
     */
    public function handles($type)
    {
        return strtolower($type) == 'awss3';
    }

    /**
     * @param array $config
     * @return \League\Flysystem\Filesystem
     */
    public function get(array $config)
    {
        $client = S3Client::factory([
            'key' => $config['key'],
            'secret' => $config['secret'],
            'region' => $config['region'],
			'base_url' => $config['base_url'],
        ]);

        return new Flysystem(new AwsS3($client, $config['bucket'], $config['root']));
    }
}
