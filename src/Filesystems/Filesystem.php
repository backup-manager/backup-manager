<?php namespace BackupManager\Filesystems;

interface Filesystem
{
    public function writeStream($provider, $path, $resource);
    public function readStream($provider, $path);
    public function delete($provider, $path);
}