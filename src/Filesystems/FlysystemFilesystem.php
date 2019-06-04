<?php namespace BackupManager\Filesystems;

use League\Flysystem\FilesystemInterface;
use League\Flysystem\MountManager;

/**
 * Class FlysystemFilesystem
 * @package BackupManager\Filesystems
 */
class FlysystemFilesystem implements Filesystem
{
    /**
     * @var array|FilesystemInterface[]
     */
    private $filesystems;

    /**
     * @var MountManager
     */
    private $manager;

    public function __construct(/* iterable */ $filesystems = [], MountManager $manager = null)
    {
        $this->filesystems = $filesystems;
        $this->manager = $manager;
    }

    /**
     * @param $type
     * @return bool
     */
    public function handles($type)
    {
        return strtolower($type) === 'flysystem';
    }

    public function get(array $config)
    {
        if (isset($config['prefix']) && null !== $this->manager) {
            return $this->manager->getFilesystem($config['prefix']);
        }

        return $this->filesystems[$config['name']];
    }
}
