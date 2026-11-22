<?php namespace BackupManager\Filesystems;

use League\Flysystem\FilesystemInterface;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\MountManager;

/**
 * Exposes Flysystem instances you construct yourself, by name or by mount
 * manager prefix.
 *
 * @package BackupManager\Filesystems
 */
class FlysystemFilesystem implements Filesystem
{
    /**
     * @var array|FilesystemInterface[]|FilesystemOperator[]
     */
    private $filesystems;

    /**
     * @var MountManager|null
     */
    private $manager;

    /**
     * @param iterable|FilesystemInterface[]|FilesystemOperator[] $filesystems keyed by name
     * @param MountManager|null $manager
     */
    public function __construct(/* iterable */ $filesystems = [], ?MountManager $manager = null)
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
        return strtolower($type ?? '') === 'flysystem';
    }

    /**
     * @param array $config
     * @return FilesystemInterface|FilesystemOperator|MountManagerFilesystem
     */
    public function get(array $config)
    {
        if (isset($config['prefix']) && null !== $this->manager) {
            // Flysystem v1
            if (method_exists($this->manager, 'getFilesystem')) {
                return $this->manager->getFilesystem($config['prefix']);
            }

            // Flysystem v2/v3 address mounted filesystems as "prefix://path".
            return new MountManagerFilesystem($this->manager, $config['prefix']);
        }

        return $this->filesystems[$config['name']];
    }
}
