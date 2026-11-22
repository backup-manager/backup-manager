<?php

declare(strict_types=1);

namespace BackupManager\Filesystems;

use League\Flysystem\FilesystemOperator;

/**
 * Exposes Flysystem instances you construct yourself, by name. A
 * MountManager is a FilesystemOperator too: register it by name and use
 * "prefix://path" destination paths.
 */
final class FlysystemFilesystem implements Filesystem
{
    /**
     * @param array<string, FilesystemOperator> $filesystems keyed by name
     */
    public function __construct(private readonly array $filesystems = [])
    {
    }

    #[\Override]
    public function handles(?string $type): bool
    {
        return strtolower($type ?? '') === 'flysystem';
    }

    #[\Override]
    public function get(array $config): FilesystemOperator
    {
        $name = $config['name'] ?? null;

        if (!is_string($name) || !isset($this->filesystems[$name])) {
            throw new \InvalidArgumentException(sprintf(
                'No Flysystem instance is registered under the name "%s". Registered: %s.',
                is_string($name) ? $name : '',
                implode(', ', array_keys($this->filesystems)) ?: 'none'
            ));
        }

        return $this->filesystems[$name];
    }
}
