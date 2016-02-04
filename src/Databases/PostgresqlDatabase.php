<?php namespace BackupManager\Databases;

use BackupManager\Config\Config;
use BackupManager\File;
use BackupManager\Shell\ShellCommand;
use BackupManager\Shell\ShellProcessor;

class PostgresqlDatabase implements Database {

    /** @var ShellProcessor */
    private $shell;
    /** @var Config */
    private $config;

    public function __construct(ShellProcessor $shell, Config $config) {
        $this->shell = $shell;
        $this->config = $config;
    }

    /**
     * @param File $file
     * @return void
     */
    public function dump(File $file) {
        $command = new ShellCommand(sprintf('PGPASSWORD=%s pg_dump --host=%s --port=%s --username=%s %s -f %s',
            escapeshellarg($this->config->get('pass')),
            escapeshellarg($this->config->get('host')),
            escapeshellarg($this->config->get('port')),
            escapeshellarg($this->config->get('user')),
            escapeshellarg($this->config->get('database')),
            escapeshellarg($file->fullPath())
        ));
        $this->shell->process($command);
    }

    /**
     * @param File $file
     * @return void
     */
    public function restore(File $file) {
        $command = new ShellCommand(sprintf('PGPASSWORD=%s psql --host=%s --port=%s --user=%s %s -f %s',
            escapeshellarg($this->config->get('pass')),
            escapeshellarg($this->config->get('host')),
            escapeshellarg($this->config->get('port')),
            escapeshellarg($this->config->get('user')),
            escapeshellarg($this->config->get('database')),
            escapeshellarg($file->fullPath())
        ));
        $this->shell->process($command);
    }
}
