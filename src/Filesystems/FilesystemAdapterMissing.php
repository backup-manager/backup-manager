<?php namespace BackupManager\Filesystems;

/**
 * Thrown when a filesystem type is configured but the Composer package that
 * provides its Flysystem adapter is not installed.
 *
 * @package BackupManager\Filesystems
 */
class FilesystemAdapterMissing extends \RuntimeException
{
    /**
     * @param string $type the filesystem type from the storage config
     * @param string $package the Composer package that provides the adapter
     * @return self
     */
    public static function forType($type, $package)
    {
        return new self(sprintf(
            'The filesystem type "%s" requires an adapter package that is not installed. Run: composer require %s',
            $type,
            $package
        ));
    }

    /**
     * For filesystem types whose adapter package only supports Flysystem 1.
     *
     * @param string $type the filesystem type from the storage config
     * @param string $package the Composer package that provides the adapter
     * @param string $alternative what to use instead with Flysystem 2 or 3
     * @return self
     */
    public static function forFlysystemV1Type($type, $package, $alternative)
    {
        return new self(sprintf(
            'The filesystem type "%s" requires %s, which only supports Flysystem 1 and is not installed. %s',
            $type,
            $package,
            $alternative
        ));
    }
}
