<?php namespace BackupManager\Databases;

use BackupManager\File;

interface Database {

    /**
     * @param File $file
     * @return void
     */
    public function dump(File $file);

    /**
     * @param File $file
     * @return void
     */
    public function restore(File $file);
}