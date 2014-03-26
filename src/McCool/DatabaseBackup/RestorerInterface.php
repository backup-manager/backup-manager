<?php namespace McCool\DatabaseBackup;

/**
 * Interface RestorerInterface
 * Restore a database from source.
 * @package McCool\DatabaseBackup\Restorers
 */
interface RestorerInterface
{
    /**
     * @param $sourceFilePath
     * @return mixed
     */
    public function restoreFromFile($sourceFilePath);
} 
