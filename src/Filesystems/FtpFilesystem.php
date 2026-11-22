<?php

declare(strict_types=1);

namespace BackupManager\Filesystems;

use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\Ftp\FtpAdapter;
use League\Flysystem\Ftp\FtpConnectionOptions;

final class FtpFilesystem implements Filesystem
{
    #[\Override]
    public function handles(?string $type): bool
    {
        return strtolower($type ?? '') === 'ftp';
    }

    #[\Override]
    public function get(array $config): FilesystemOperator
    {
        if (!class_exists(FtpAdapter::class)) {
            throw FilesystemAdapterMissing::forType('Ftp', 'league/flysystem-ftp');
        }

        return new Flysystem(new FtpAdapter(FtpConnectionOptions::fromArray(Scalars::cast(
            $config,
            ['port', 'timeout'],
            ['ssl', 'passive', 'utf8', 'ignorePassiveAddress', 'timestampsOnUnixListingsEnabled', 'recurseManually']
        ))));
    }
}
