<?php namespace BigName\DatabaseBackup\Filesystems;

use Aws\S3\S3Client;

/**
 * Class LocalFilesystem
 * @package BigName\DatabaseBackup\Filesystems
 */
class AwsS3Filesystem implements Filesystem
{
    /**
     * Get the name identifier of the filesystem. The name
     * is generally derived from the Flysystem driver name.
     * @param string $type
     * @return string
     */
    public function handles($type)
    {
        return $type == 'AwsS3';
    }

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
