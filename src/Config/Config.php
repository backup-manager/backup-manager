<?php namespace BigName\BackupManager\Config;

/**
 * Class Config
 * @package BigName\BackupManager\Config
 */
class Config
{
    /**
     * @var mixed
     */
    private $config;

    /**
     * @param $path
     * @return static
     * @throws ConfigFileNotFound
     */
    public static function fromPhpFile($path)
    {
        if ( ! file_exists($path)) {
            throw new ConfigFileNotFound('The configuration file "' . $path . '" could not be found.');
        }
        /** @noinspection PhpIncludeInspection */
        return new static(require $path);
    }

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param $name
     * @param null $field
     * @return mixed
     * @throws ConfigFieldNotFound
     * @throws ConfigNotFoundForConnection
     */
    public function get($name, $field = null, $default = null)
    {
        if ( ! array_key_exists($name, $this->config) && is_null($default)) {
            throw new ConfigNotFoundForConnection("Could not find configuration for connection {$name}");
        }
        if ($field) {
            return $this->getConfigField($name, $field, $default);
        }
        return array_key_exists($name, $this->config)
	            ? $this->config[$name]
	            : $default;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->config;
    }

    /**
     * @param $name
     * @param $field
     * @return mixed
     * @throws ConfigFieldNotFound
     */
    private function getConfigField($name, $field, $default = null)
    {
        if ( ! array_key_exists($field, $this->config[$name])) {
	        if (is_null($default)) {
	            throw new ConfigFieldNotFound("Could not find field {$field} in configuration for connection type {$name}");
	        }
	        return $default;
        }
        return $this->config[$name][$field];
    }
} 
