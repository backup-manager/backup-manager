<?php namespace BackupManager\Config;

class ConfigReaderTypeDoesNotExist extends \Exception {

    public function __construct($type) {
        parent::__construct("Config Reader type [{$type}] does not exist.");
    }
}