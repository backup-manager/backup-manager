<?php namespace BackupManager\Console;

class CopyCommand extends Command {

    protected function configure() {
        $this
            ->setName('copy')
            ->setDescription('Copies a third parties\' configuration to Backup Manager configuration.');
    }

    protected function handle() {
        // TODO: Implement handle() method.
    }
}