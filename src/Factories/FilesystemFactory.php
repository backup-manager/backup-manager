<?php namespace McCool\DatabaseBackup\Factories;

use Aws\S3\S3Client;
use League\Flysystem\Adapter\AwsS3;
use League\Flysystem\Filesystem;
use McCool\DatabaseBackup\Config;

class FilesystemFactory
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function makeFilesystemFor($name)
    {
        $type = $this->config->get($name, 'type');
        $method = "make{$type}Filesystem";
        if (!method_exists($this, $method)) {
            return new \Exception("Connection type {$name} is not currently supported.");
        }
        return $this->{$method}($name);
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function makeAwsS3Filesystem($name)
    {
        $client = S3Client::factory([
            'key' => $this->config->get($name, 'key'),
            'secret' => $this->config->get($name, 'secret'),
            'region' => $this->config->get($name, 'region'),
        ]);

        return new Filesystem(new AwsS3($client, $this->config->get($name, 'bucket')));
    }
}
