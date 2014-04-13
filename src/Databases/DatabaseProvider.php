<?php namespace BigName\BackupManager\Databases;

use BigName\BackupManager\Config\Config;

/**
 * Class DatabaseProvider
 * @package BigName\BackupManager\Databases
 */
class DatabaseProvider
{
    /**
     * @var \BigName\BackupManager\Config\Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param $name
     * @return Database
     * @throws DatabaseTypeNotSupported
     * @throws \BigName\BackupManager\Config\ConfigNotFoundForConnection
     */
    public function get($name)
    {
        $type = $this->config->get($name, 'type');
        if (is_null($type)) {
            return new NullDatabase([]);
        }

        $class = $this->getClass($type);
        if ( ! class_exists($class)) {
            throw new DatabaseTypeNotSupported('The requested database type "' . $class . '" is not currently supported.');
        }
        return new $class($this->config->get($name));
    }

    /**
     * @param $type
     * @return string
     */
    private function getClass($type)
    {
        $type = ucfirst(strtolower($type));
        return "BigName\\BackupManager\\Databases\\{$type}Database";
    }

    /**
     * @return array
     */
    public function getAvailableProviders()
    {
        return array_keys($this->config->getItems());
    }
} 
