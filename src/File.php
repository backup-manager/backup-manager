<?php namespace BackupManager;

use League\Flysystem\Util;

class File {

    /** @var string */
    private $filePath;
    /** @var string */
    private $root;

    public function __construct($filePath = null, $root = '') {
        $this->filePath = trim($filePath) ?: uniqid();
        $this->root = rtrim($root, '/');
    }

    /**
     * @return string
     */
    public function filePath() {
        return $this->filePath;
    }

    public function root() {
        return $this->root;
    }

    public function fullPath() {
        if ($this->root == '')
            return $this->filePath;
        return "{$this->root}/{$this->filePath}";
    }
}