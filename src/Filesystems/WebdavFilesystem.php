<?php

declare(strict_types=1);

namespace BackupManager\Filesystems;

use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\WebDAV\WebDAVAdapter;
use Sabre\DAV\Client;

final class WebdavFilesystem implements Filesystem
{
    #[\Override]
    public function handles(?string $type): bool
    {
        return strtolower($type ?? '') === 'webdav';
    }

    #[\Override]
    public function get(array $config): FilesystemOperator
    {
        if (!class_exists(WebDAVAdapter::class)) {
            throw FilesystemAdapterMissing::forType('Webdav', 'league/flysystem-webdav');
        }

        return new Flysystem(new WebDAVAdapter(new Client($config), (string) ($config['prefix'] ?? '')));
    }
}
