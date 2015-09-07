<?php namespace BackupManager\Filesystems;

use League\Flysystem\AwsS3v2\AwsS3Adapter;
use Aws\S3\S3Client;
use League\Flysystem\Filesystem as Flysystem;

/**
 * Class GcsFilesystem
 * @package BackupManager\Filesystems
 */
class GcsFilesystem implements Filesystem {

    /**
     * @param $type
     * @return bool
     */
    public function handles($type) {
        return strtolower($type) == 'gcs';
    }

    /**
     * @param array $config
     * @return \League\Flysystem\Filesystem
     */
    public function get(array $config) {
        $client = S3Client::factory([
            'key'      => $config['key'],
            'secret'   => $config['secret'],
            'base_url' => 'https://storage.googleapis.com',
        ]);

        return new Flysystem(new AwsS3Adapter($client, $config['bucket'], $config['root']));
    }
}
