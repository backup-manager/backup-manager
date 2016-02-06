<?php namespace BackupManager\Console;

class RestoreCommand extends ConfigurationDependentCommand {

    protected function configure() {
        $this
            ->setName('restore')
            ->setDescription('Restore a database dump on a service.');
    }

    protected function handle() {
        // TODO: Implement handle() method.
    }
}