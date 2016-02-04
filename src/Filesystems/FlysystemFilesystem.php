<?php namespace BackupManager\Filesystems;

use League\Flysystem\MountManager;

class FlysystemFilesystem implements Filesystem {

    /** @var MountManager */
    private $files;

    public function __construct(MountManager $files) {
        if ( ! $files->getAdapter('local://'))
            throw new NoLocalFilesystemAvailable;
        $this->files = $files;
    }
    
    public function writeStream($provider, $path, $resource) {
        $this->files->writeStream("{$provider}://{$path}", $resource);
    }

    public function readStream($provider, $path) {
        return $this->files->readStream("{$provider}://{$path}");
    }

    public function delete($provider, $path) {
        $this->files->delete("{$provider}://{$path}");
    }
}