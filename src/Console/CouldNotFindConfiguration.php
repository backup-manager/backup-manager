<?php namespace BackupManager\Console;

class CouldNotFindConfiguration extends \Exception {

    public function __construct() {
        parent::__construct('<error>Could not find configuration file in this directory nor was it set. Please use "backupmanager init" to create your configuration.</error>');
    }
}