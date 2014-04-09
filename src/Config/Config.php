<?php namespace BigName\BackupManager\Config;

class Config
{
    private $config;

    public function __construct($path)
    {
        if ( ! file_exists($path)) {
            throw new ConfigFileNotFound('The configuration file "' . $path . '" could not be found.');
        }
        $this->config = require $path;
    }

    public function get($name, $field = null)
    {
        if ( ! array_key_exists($name, $this->config)) {
            throw new ConfigNotFoundForConnection("Could not find configuration for connection {$name}");
        }
        if ($field) {
            return $this->getConfigField($name, $field);
        }
        return $this->config[$name];
    }

    private function getConfigField($name, $field)
    {
        if (!array_key_exists($field, $this->config[$name])) {
            throw new ConfigFieldNotFound("Could not find field {$field} in configuration for connection type {$name}");
        }
        return $this->config[$name][$field];
    }
} 
