<?php namespace BigName\DatabaseBackup\Filesystems;

use League\Flysystem\Adapter\AwsS3;
use Aws\S3\S3Client;

class Awss3Filesystem implements Filesystem
{
    public function get(array $config)
    {
        $client = S3Client::factory([
            'key' => $config['key'],
            'secret' => $config['secret'],
            'region' => $config['region'],
        ]);

        return new \League\Flysystem\Filesystem(new AwsS3($client, $config['bucket']));
    }
}
