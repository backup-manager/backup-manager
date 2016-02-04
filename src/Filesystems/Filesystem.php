<?php namespace BackupManager\Filesystems;

interface Filesystem {

    public function writeStream($provider, $filePath, $resource);
    public function readStream($provider, $filePath);
    public function delete($provider, $filePath);
    public function root($provider);
}