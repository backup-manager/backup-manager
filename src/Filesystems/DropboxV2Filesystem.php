<?php namespace BackupManager\Filesystems;

use League\Flysystem\Filesystem as Flysystem;

/**
 * @package BackupManager\Filesystems
 */
class DropboxV2Filesystem extends DropboxFilesystem
{
    /**
     * Test fitness of visitor.
     * @param $type
     * @return bool
     */
    public function handles($type)
    {
        return strtolower($type ?? '') == 'dropboxv2';
    }
}
