<?php

namespace spec\BackupManager\Support;

/**
 * The filesystem operations the storage tasks use, common to every
 * Flysystem major. Specs double this instead of a version-specific type.
 */
interface StorageDouble
{
    public function readStream($path);

    public function writeStream($path, $contents, array $config = []);

    public function delete($path);
}
