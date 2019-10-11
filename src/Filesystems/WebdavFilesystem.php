<?php namespace BackupManager\Filesystems;

use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\WebDAV\WebDAVAdapter;
use Sabre\DAV\Client;

/**
 * Class WebdavFilesystem
 * @package BackupManager\Filesystems
 */
class WebdavFilesystem implements Filesystem {

    /**
     * Test fitness of visitor.
     * @param $type
     * @return bool
     */
    public function handles($type) {
        return strtolower($type) === 'webdav';
    }

    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config) {
        return new Flysystem(new WebDAVAdapter(new Client($config), $config['prefix']));
    }
}
