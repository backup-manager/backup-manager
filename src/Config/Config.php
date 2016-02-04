<?php namespace BackupManager\Config;

class Config {

    /** @var array */
    private $items;

    public function __construct($items = []) {
        $this->items = $items;
    }

    public function get($key) {
        if (!array_key_exists($key, $this->items))
            throw new ConfigItemNotFound("Could not find item [{$key}].");
        return $this->items[$key];
    }

    public function all() {
        return $this->items;
    }
}