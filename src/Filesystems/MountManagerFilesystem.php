<?php namespace BackupManager\Filesystems;

use League\Flysystem\MountManager;

/**
 * Presents one prefix of a Flysystem v2/v3 MountManager as a filesystem with
 * the operations the storage tasks use.
 *
 * @internal
 * @package BackupManager\Filesystems
 */
final class MountManagerFilesystem
{
    /** @var MountManager */
    private $manager;
    /** @var string */
    private $prefix;

    /**
     * @param MountManager $manager
     * @param string $prefix
     */
    public function __construct(MountManager $manager, $prefix)
    {
        $this->manager = $manager;
        $this->prefix = $prefix;
    }

    /**
     * @param string $path
     * @return resource
     */
    public function readStream($path)
    {
        return $this->manager->readStream($this->path($path));
    }

    /**
     * @param string $path
     * @param resource $contents
     * @param array $config
     * @return void
     */
    public function writeStream($path, $contents, array $config = [])
    {
        $this->manager->writeStream($this->path($path), $contents, $config);
    }

    /**
     * @param string $path
     * @return void
     */
    public function delete($path)
    {
        $this->manager->delete($this->path($path));
    }

    /**
     * @param string $path
     * @return string
     */
    private function path($path)
    {
        return $this->prefix . '://' . ltrim($path, '/');
    }
}
