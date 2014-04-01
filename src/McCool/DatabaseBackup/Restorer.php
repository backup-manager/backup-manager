<?php namespace McCool\DatabaseBackup;

/**
 * Interface Restorer
 * Restore a database from source.
 * @package McCool\DatabaseBackup\Restorers
 */
interface Restorer
{
    /**
     * @param $sourceFilePath
     * @return mixed
     */
    public function restoreFromFile($sourceFilePath);
} 
