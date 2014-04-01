<?php namespace McCool\DatabaseBackup;

/**
 * Interface Storer
 * @package McCool\DatabaseBackup
 */
Interface Storer
{
    /**
     * Stores the backup to the given storage provider.
     * @param  string $filename
     * @return void
     */
    public function store($filename);
}
