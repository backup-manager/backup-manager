<?php namespace BackupManager\Tasks;

/**
 * Interface Task
 * @package BackupManager\Tasks
 */
interface Task {

    /**
     * @return mixed
     */
    public function execute();
} 
