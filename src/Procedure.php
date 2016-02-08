<?php namespace BackupManager;

class Procedure {

    private $name;
    private $database;
    private $destinations;
    private $compression;

    public function __construct($name, $database, $destinations, $compression) {
        $this->name = $name;
        $this->database = $database;
        $this->destinations = $destinations;
        $this->compression = $compression;
    }

    public function name() {
        return $this->name;
    }

    public function database() {
        return $this->database;
    }

    public function destinations() {
        $destinations = [];
        foreach ($this->destinations as $provider => $filePath)
            $destinations[] = new RemoteFile($provider, new File($filePath));
        return $destinations;
    }

    public function compression() {
        return $this->compression;
    }
}
