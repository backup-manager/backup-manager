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
     * @var \BigName\BackupManager\Databases\Database []
     */
    private $databases = [];

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param Database $database
     */
    public function add(Database $database)
    {
        $this->databases[] = $database;
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
        foreach ($this->databases as $database) {
            if ($database->handles($type)) {
                $database->setConfig($this->config->get($name));
                return $database;
            }
        }
        throw new DatabaseTypeNotSupported("The requested database type {$type} is not currently supported.");
    }

    /**
     * @return array
     */
    public function getAvailableProviders()
    {
        return array_keys($this->config->getItems());
    }
} 
