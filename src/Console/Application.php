<?php namespace BackupManager\Console;

use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication {

    public function __construct($version) {
        parent::__construct('Backup Manager', $version);

        $this->add(new InitCommand);
        $this->add(new DumpCommand);
        $this->add(new RestoreCommand);
        $this->add(new CopyCommand);
    }
}