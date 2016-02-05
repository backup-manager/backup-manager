<?php namespace BackupManager\Console;

use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication {

    public function __construct() {
        parent::__construct('Backup Manager', '2.0');

        $this->add(new InitCommand);
        $this->add(new DumpCommand);
        $this->add(new RestoreCommand);
        $this->add(new CopyCommand);
    }
}