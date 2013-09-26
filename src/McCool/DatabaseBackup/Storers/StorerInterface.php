<?php namespace McCool\DatabaseBackup\Storers;

Interface StorerInterface
{
    /**
     * Sets the filename for the backup.
     *
     * @param  string  $filename
     * @return void
     */
    public function setInputFilename($filename);

    /**
     * Stores the backup to the given storage provider.
     *
     * @return void
     */
    public function store();
}