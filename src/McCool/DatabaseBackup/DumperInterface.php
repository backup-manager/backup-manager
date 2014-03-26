<?php namespace McCool\DatabaseBackup\Dumpers;

interface DumperInterface
{
    /**
     * @param string $destinationPath
     * @return void
     */
    public function dump($destinationPath);
}
