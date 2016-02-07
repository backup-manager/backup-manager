<?php namespace BackupManager\Databases;

use BackupManager\Config\Config;
use BackupManager\Shell\ShellProcessor;

class DatabaseFactory {

    /** @var ShellProcessor */
    private $shell;
    /** @var Config */
    private $config;

    public function __construct(ShellProcessor $shell, Config $config) {
        $this->shell = $shell;
        $this->config = $config;
    }

    public function make($connectionName) {
        $connectionConfig = new Config($this->config->get("databases.connections.{$connectionName}"));
        switch (strtolower($connectionConfig->get('driver'))) {
            case 'mysql':
                return $this->makeMysqlDatabase($connectionConfig);
            case 'postgresql':
                return $this->makePostgresqlDatabase($connectionConfig);
        }
    }

    private function makeMysqlDatabase(Config $config) {
        return new MysqlDatabase($this->shell, $config);
    }

    private function makePostgresqlDatabase(Config $config) {
        return new PostgresqlDatabase($this->shell, $config);
    }
}
