<?php

namespace spec\BackupManager\Support;

/**
 * Reads the adapter out of a Flysystem instance in any Flysystem major.
 */
final class Adapters
{
    /**
     * @param object $filesystem
     * @return object
     */
    public static function of($filesystem)
    {
        // Flysystem v1
        if (method_exists($filesystem, 'getAdapter')) {
            return $filesystem->getAdapter();
        }

        // Flysystem v2/v3 keep the adapter private.
        $property = new \ReflectionProperty($filesystem, 'adapter');
        $property->setAccessible(true);

        return $property->getValue($filesystem);
    }

    /**
     * @param string[] $classes adapter classes in the factory's order of preference
     * @return string|null the first one that is installed
     */
    public static function firstInstalled(array $classes)
    {
        foreach ($classes as $class) {
            if (class_exists($class)) {
                return $class;
            }
        }

        return null;
    }
}
