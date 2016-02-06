<?php namespace BackupManager\Console;

class InitCommand extends Command {

    protected function configure() {
        $this
            ->setName('init')
            ->setDescription('Initialize the Backup Manager configuration.');
    }

    protected function handle() {
        // TODO: Implement handle() method.
    }
}