<?php namespace BackupManager\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class ConfigurationDependentCommand extends Command {

    protected function execute(InputInterface $input, OutputInterface $output) {
        // Handle and parse configuration here
        if ( ! $this->configurationFileExists())
            throw new CouldNotFindConfiguration;


        parent::execute($input, $output);
    }

    private function configurationFileExists() {
        return $this->input()->getOption('config') !== null || file_exists(getcwd() . '/backupmanager.yml');
    }
}