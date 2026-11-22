<?php namespace BackupManager\Filesystems;

use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\WebDAV\WebDAVAdapter;
use Sabre\DAV\Client;

/**
 * Class WebdavFilesystem
 * @package BackupManager\Filesystems
 */
class WebdavFilesystem implements Filesystem
{
    /**
     * @param $type
     * @return bool
     */
    public function handles($type)
    {
        return strtolower($type ?? '') === 'webdav';
    }

    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config)
    {
        if (! class_exists(WebDAVAdapter::class)) {
            throw FilesystemAdapterMissing::forType('Webdav', 'league/flysystem-webdav');
        }

        // The adapter class name is the same in every major; v3 requires a string prefix.
        $prefix = isset($config['prefix']) ? $config['prefix'] : null;
        if ($prefix === null && interface_exists(FilesystemOperator::class)) {
            $prefix = '';
        }

        return new Flysystem(new WebDAVAdapter(new Client($config), $prefix));
    }
}
