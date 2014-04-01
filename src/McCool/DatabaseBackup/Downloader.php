<?php namespace McCool\DatabaseBackup\Downloaders;

/**
 * Interface Downloader
 * @package McCool\DatabaseBackup\Downloaders
 */
interface Downloader
{
    /**
     * @param $filePath
     * @param $localStorageDirectory
     * @return mixed
     */
    public function download($filePath, $localStorageDirectory);
}
