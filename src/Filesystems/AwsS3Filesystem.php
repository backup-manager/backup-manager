<?php namespace BigName\DatabaseBackup\Filesystems;

use Aws\S3\S3Client;

class AwsS3Filesystem implements Filesystem
{
    public function get(array $config)
    {
        $client = S3Client::factory([
            'key' => $config['key'],
            'secret' => $config['secret'],
            'region' => $config['region'],
        ]);

        return new \League\Flysystem\Filesystem(new \League\Flysystem\Adapter\AwsS3($client, $config['bucket']));
    }
}
