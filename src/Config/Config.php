<?php namespace BackupManager\Config;

class Config {

    /** @var array */
    private $items;

    public function __construct($items = []) {
        $this->items = $items;
    }

    public function get($key) {
        $items = $this->items;
        if (isset($items[$key]))
            return $items[$key];

        foreach (explode('.', $key) as $segment)
            $items = $items[$segment];

        return $items;
    }

    public function all() {
        return $this->items;
    }
}