<?php

declare(strict_types=1);

namespace BackupManager\Filesystems;

use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\Local\LocalFilesystemAdapter;

final class LocalFilesystem implements Filesystem
{
    #[\Override]
    public function handles(?string $type): bool
    {
        return strtolower($type ?? '') === 'local';
    }

    #[\Override]
    public function get(array $config): FilesystemOperator
    {
        return new Flysystem(new LocalFilesystemAdapter($config['root']));
    }
}
