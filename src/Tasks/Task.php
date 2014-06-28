<?php namespace BigName\BackupManager\Tasks;

/**
 * Interface Task
 * @package BigName\BackupManager\Tasks
 */
interface Task
{
    /**
     * @return mixed
     */
    public function execute();
} 
