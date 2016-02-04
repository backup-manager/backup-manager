<?php namespace BackupManager;

use BackupManager\File;

class RemoteFile {

    private $provider;
    private $file;

    public function __construct($provider, File $file) {
        $this->provider = $provider;
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function provider() {
        return $this->provider;
    }

    /**
     * @return File
     */
    public function file() {
        return $this->file;
    }

    /**
     * @return string
     */
    public function path() {
        return $this->file->path();
    }
}