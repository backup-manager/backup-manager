<?php namespace BackupManager;

class File {

    /** @var string */
    private $filePath;

    public function __construct($filePath = null) {
        $this->filePath = trim($filePath) ?: uniqid();
    }

    /**
     * @return string
     */
    public function path() {
        return $this->filePath;
    }
}