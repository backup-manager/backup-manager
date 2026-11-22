<?php

declare(strict_types=1);

namespace BackupManager\Filesystems;

use League\Flysystem\FilesystemOperator;

/**
 * A filesystem factory: reports which filesystem type it handles and builds a
 * Flysystem instance from that type's storage config.
 */
interface Filesystem
{
    public function handles(?string $type): bool;

    /**
     * @param array<string, mixed> $config
     */
    public function get(array $config): FilesystemOperator;
}
