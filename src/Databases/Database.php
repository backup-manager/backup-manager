<?php namespace BackupManager\Databases;

use BackupManager\File;

interface Database
{
    /**
     * @param File $file
     * @return string
     */
    public function dump(File $file);

    /**
     * @param File $file
     * @return string
     */
    public function restore(File $file);
}