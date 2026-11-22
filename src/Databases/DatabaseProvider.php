<?php

declare(strict_types=1);

namespace BackupManager\Databases;

use BackupManager\Config\Config;
use BackupManager\Config\ConfigFieldNotFound;
use BackupManager\Config\ConfigNotFoundForConnection;

class DatabaseProvider
{
    /** @var list<Database> */
    private array $databases = [];

    public function __construct(private readonly Config $config)
    {
    }

    public function add(Database $database): void
    {
        $this->databases[] = $database;
    }

    /**
     * @throws DatabaseTypeNotSupported
     * @throws ConfigNotFoundForConnection
     * @throws ConfigFieldNotFound
     */
    public function get(string $name): Database
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
     * @return list<string> connection names
     */
    public function getAvailableProviders(): array
    {
        return array_keys($this->config->getItems());
    }
}
