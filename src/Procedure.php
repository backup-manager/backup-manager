<?php namespace BackupManager;

class Procedure {

    private $name;
    private $database;
    private $storageProvider;
    private $filePath;
    private $compression;

    public function __construct($name, $database, $storageProvider, $filePath, $compression) {
        $this->name = $name;
        $this->database = $database;
        $this->storageProvider = $storageProvider;
        $this->filePath = $filePath;
        $this->compression = $compression;
    }

    public function name() {
        return $this->name;
    }

    public function database() {
        return $this->database;
    }

    public function storageProvider() {
        return $this->storageProvider;
    }

    public function filePath() {
        return $this->filePath;
    }

    public function compression() {
        return $this->compression;
    }
}
