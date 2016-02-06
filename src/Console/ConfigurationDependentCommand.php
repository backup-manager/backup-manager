<?php namespace BackupManager\Console;

use BackupManager\File;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class ConfigurationDependentCommand extends Command {

    protected function execute(InputInterface $input, OutputInterface $output) {
        // Handle and parse configuration here
        if ( ! $file = $this->configurationFile())
            throw new CouldNotFindConfiguration;

        


        parent::execute($input, $output);
    }

    private function configurationFile() {
        $paths = ['backupmanager.yml', 'backupmanager.yml.dist'];
        if ($this->input()->getOption('config'))
            $paths[] = $this->input()->getOption('config');
        foreach ($paths as $path)
            if (file_exists(getcwd() . "/{$path}"))
                return new File($path, getcwd());

        return false;
    }
}