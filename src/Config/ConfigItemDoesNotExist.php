<?php namespace BackupManager\Config;

class ConfigItemDoesNotExist extends \Exception {

    public function __construct($itemName) {
        parent::__construct("Config item [{$itemName}] does not exist.");
    }
}