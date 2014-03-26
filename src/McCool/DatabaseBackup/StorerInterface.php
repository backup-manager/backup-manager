<?php namespace McCool\DatabaseBackup;

Interface StorerInterface
{
    /**
     * Stores the backup to the given storage provider.
     * @param  string $filename
     * @return void
     */
    public function store($filename);
}
