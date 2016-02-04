<?php namespace BackupManager\Filesystems;

use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\MountManager;

class FlysystemFilesystem implements Filesystem {

    /** @var MountManager */
    private $files;

    public function __construct(MountManager $files) {
        if ( ! $files->getAdapter('local://'))
            throw new NoLocalFilesystemAvailable;
        $this->files = $files;
    }
    
    public function writeStream($provider, $filePath, $resource) {
        $this->files->writeStream("{$provider}://{$filePath}", $resource);
    }

    public function readStream($provider, $filePath) {
        return $this->files->readStream("{$provider}://{$filePath}");
    }

    public function delete($provider, $filePath) {
        $this->files->delete("{$provider}://{$filePath}");
    }

    public function root($provider) {
        /** @var AbstractAdapter $adapter */
        $adapter = $this->files->getAdapter("{$provider}://");
        return rtrim($adapter->getPathPrefix(), '/');
    }
}