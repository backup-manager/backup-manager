<?php namespace BigName\BackupManager\Commands;

/**
 * Interface Command
 * @package BigName\BackupManager\Commands
 */
interface Command
{
    /**
     * @return mixed
     */
    public function execute();
} 
