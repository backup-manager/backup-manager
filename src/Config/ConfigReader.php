<?php namespace BackupManager\Config;

use BackupManager\File;

interface ConfigReader {

    /**
     * @param File $file
     * @return Config
     */
    public function read(File $file);
}