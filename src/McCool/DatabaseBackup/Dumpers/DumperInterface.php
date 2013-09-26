<?php namespace McCool\DatabaseBackup\Dumpers;

interface DumperInterface
{
    /**
     * Dumps the backup into the database.
     *
     * @return void.
     */
    public function dump();

    /**
     * Returns the filename for the backup.
     *
     * @return string
     */
    public function getOutputFilename();
}