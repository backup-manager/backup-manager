<?php

declare(strict_types=1);

namespace BackupManager\Config;

/**
 * Configuration entries keyed by connection name.
 */
class Config
{
    /**
     * @param array<string, array<string, mixed>> $config
     */
    final public function __construct(private readonly array $config)
    {
    }

    /**
     * @throws ConfigFileNotFound
     */
    public static function fromPhpFile(string $path): static
    {
        if (!file_exists($path)) {
            throw new ConfigFileNotFound('The configuration file "' . $path . '" could not be found.');
        }

        return new static(require $path);
    }

    /**
     * @return mixed the connection's config array, or one field of it
     * @throws ConfigNotFoundForConnection
     * @throws ConfigFieldNotFound
     */
    public function get(string $name, ?string $field = null): mixed
    {
        if (!array_key_exists($name, $this->config)) {
            throw new ConfigNotFoundForConnection("Could not find configuration for connection {$name}");
        }

        if ($field !== null && $field !== '') {
            return $this->getConfigField($name, $field);
        }

        return $this->config[$name];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function getItems(): array
    {
        return $this->config;
    }

    /**
     * @throws ConfigFieldNotFound
     */
    private function getConfigField(string $name, string $field): mixed
    {
        if (!array_key_exists($field, $this->config[$name])) {
            throw new ConfigFieldNotFound("Could not find field {$field} in configuration for connection type {$name}");
        }

        return $this->config[$name][$field];
    }
}
