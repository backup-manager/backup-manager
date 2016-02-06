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

    public function make($type) {
        switch (strtolower($type)) {
            case 'mysql':
                return $this->makeMysqlDatabase();
                break;
            case 'postgresql':
                return $this->makePostgresqlDatabase();
                break;
        }
    }

    private function makeMysqlDatabase() {
        return new MysqlDatabase($this->shell, $this->config);
    }

    private function makePostgresqlDatabase() {
        return new PostgresqlDatabase($this->shell, $this->config);
    }
}
