<?php namespace McCool\DatabaseBackup\Dumpers;

/**
 * Interface Dumper
 * @package McCool\DatabaseBackup\Dumpers
 */
interface Dumper
{
    /**
     * @param string $destinationPath
     * @return void
     */
    public function dump($destinationPath);
}
