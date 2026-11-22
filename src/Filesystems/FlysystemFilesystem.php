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
    /** @var array<string, FilesystemOperator> */
    private readonly array $filesystems;

    /**
     * @param iterable<string, FilesystemOperator> $filesystems keyed by name
     */
    public function __construct(iterable $filesystems = [])
    {
        $this->filesystems = is_array($filesystems) ? $filesystems : iterator_to_array($filesystems);
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
