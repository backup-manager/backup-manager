<?php

declare(strict_types=1);

namespace BackupManager\Filesystems;

use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\PhpseclibV3\SftpAdapter;
use League\Flysystem\PhpseclibV3\SftpConnectionProvider;

final class SftpFilesystem implements Filesystem
{
    #[\Override]
    public function handles(?string $type): bool
    {
        return strtolower($type ?? '') === 'sftp';
    }

    #[\Override]
    public function get(array $config): FilesystemOperator
    {
        if (!class_exists(SftpAdapter::class)) {
            throw FilesystemAdapterMissing::forType('Sftp', 'league/flysystem-sftp-v3');
        }

        return new Flysystem(new SftpAdapter(
            SftpConnectionProvider::fromArray($this->connectionOptions($config)),
            (string) ($config['root'] ?? '')
        ));
    }

    /**
     * - empty credentials are absent (the provider tries any non-null value);
     * - with a private key and no 'passphrase', 'password' unlocks the key;
     * - scalars are cast, because the provider is strictly typed.
     *
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    private function connectionOptions(array $config): array
    {
        foreach (['password', 'privateKey', 'passphrase'] as $key) {
            if (isset($config[$key]) && $config[$key] === '') {
                $config[$key] = null;
            }
        }

        if (isset($config['privateKey']) && !isset($config['passphrase']) && isset($config['password'])) {
            $config['passphrase'] = $config['password'];
        }

        return Scalars::cast($config, ['port', 'timeout', 'maxTries'], ['useAgent']);
    }
}
