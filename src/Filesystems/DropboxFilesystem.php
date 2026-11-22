<?php

declare(strict_types=1);

namespace BackupManager\Filesystems;

use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\FilesystemOperator;
use Spatie\Dropbox\Client;
use Spatie\FlysystemDropbox\DropboxAdapter;

final class DropboxFilesystem implements Filesystem
{
    #[\Override]
    public function handles(?string $type): bool
    {
        return strtolower($type ?? '') === 'dropbox';
    }

    #[\Override]
    public function get(array $config): FilesystemOperator
    {
        if (!class_exists(DropboxAdapter::class)) {
            throw FilesystemAdapterMissing::forType('Dropbox', 'spatie/flysystem-dropbox');
        }

        return new Flysystem(new DropboxAdapter(new Client($config['token']), (string) ($config['root'] ?? '')));
    }
}
