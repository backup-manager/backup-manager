<?php namespace McCool\DatabaseBackup\Downloaders;

/**
 * Interface DownloaderInterface
 * @package McCool\DatabaseBackup\Downloaders
 */
interface DownloaderInterface
{
    /**
     * @param $filePath
     * @param $localStorageDirectory
     * @return mixed
     */
    public function download($filePath, $localStorageDirectory);
}
