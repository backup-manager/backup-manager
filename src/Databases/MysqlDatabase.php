<?php namespace BackupManager\Databases;

use BackupManager\Config\Config;
use BackupManager\Shell\ShellCommand;
use BackupManager\Shell\ShellProcessFailed;
use BackupManager\Shell\ShellProcessor;
use BackupManager\File;

class MysqlDatabase implements Database {

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
     * @throws ShellProcessFailed
     */
    public function dump(File $file) {
        $command = new ShellCommand(sprintf('mysqldump --routines --host=%s --port=%s --user=%s --password=%s %s > %s',
            escapeshellarg($this->config->get('host')),
            escapeshellarg($this->config->get('port')),
            escapeshellarg($this->config->get('user')),
            escapeshellarg($this->config->get('pass')),
            escapeshellarg($this->config->get('database')),
            escapeshellarg($file->fullPath())
        ));
        $this->shell->process($command);
    }

    /**
     * @param File $file
     * @return void
     * @throws ShellProcessFailed
     */
    public function restore(File $file) {
        $command = new ShellCommand(sprintf('mysql --host=%s --port=%s --user=%s --password=%s %s -e "source %s"',
            escapeshellarg($this->config->get('host')),
            escapeshellarg($this->config->get('port')),
            escapeshellarg($this->config->get('user')),
            escapeshellarg($this->config->get('pass')),
            escapeshellarg($this->config->get('database')),
            $file->fullPath()
        ));
        $this->shell->process($command);
    }
}