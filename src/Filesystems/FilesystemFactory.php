<?php namespace BackupManager\Filesystems;

use Aws\S3\S3Client;
use BackupManager\Config\Config;
use League\Flysystem\Adapter\Local;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem as LeagueFlysystem;
use League\Flysystem\MountManager;

class FilesystemFactory {

    /** @var Config */
    private $config;

    public function __construct(Config $config) {
        $this->config = $config;
    }

    public function make() {
        $providers = $this->config->get('storage.providers');
        $filesystems = [];
        foreach ($providers as $name => $provider)
            $filesystems[$name] = $this->makeFilesystem($provider);
        return new FlysystemFilesystem(new MountManager($filesystems));
    }

    private function makeFilesystem($provider) {
        switch (strtolower($provider['driver'])) {
            case 'local':
                return new LeagueFlysystem(new Local($provider['root']));
            case 's3':
                $client = new S3Client([
                    'credentials' => [
                        'key' => $provider['key'],
                        'secret' => $provider['secret'],
                    ],
                    'region' => $provider['region'],
                    'version' => isset($provider['version']) ? $provider['version'] : 'latest',
                ]);
                return new LeagueFlysystem(new AwsS3Adapter($client, $provider['bucket'], $provider['root']));
        }
    }
}