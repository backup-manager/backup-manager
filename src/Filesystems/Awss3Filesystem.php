<?php

declare(strict_types=1);

namespace BackupManager\Filesystems;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\FilesystemOperator;

final class Awss3Filesystem implements Filesystem
{
    #[\Override]
    public function handles(?string $type): bool
    {
        return strtolower($type ?? '') === 'awss3';
    }

    #[\Override]
    public function get(array $config): FilesystemOperator
    {
        if (!class_exists(AwsS3V3Adapter::class)) {
            throw FilesystemAdapterMissing::forType('AwsS3', 'league/flysystem-aws-s3-v3');
        }

        $client = new S3Client([
            'credentials' => [
                'key' => $config['key'],
                'secret' => $config['secret'],
            ],
            'region' => $config['region'],
            'version' => $config['version'] ?? 'latest',
            'endpoint' => $config['endpoint'] ?? null,
            'use_path_style_endpoint' => $config['use_path_style_endpoint'] ?? false,
        ]);

        return new Flysystem(new AwsS3V3Adapter($client, $config['bucket'], (string) ($config['root'] ?? '')));
    }
}
