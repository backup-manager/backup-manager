<?php namespace McCool\DatabaseBackup;

class Config
{
    private $config;

    public function __construct($path)
    {
        $this->config = require $path;
    }

    /**
     * @param $name
     * @param $field
     * @return \Exception
     */
    public function get($name, $field)
    {
        if ( ! array_key_exists($name, $this->config)) {
            return new \Exception("Could not find configuration for connection type {$name}");
        }
        if ( ! array_key_exists($field, $this->config[$name])) {
            return new \Exception("Could not find field {$field} in configuration for connection type {$name}");
        }
        return $this->config[$name][$field];
    }
} 
