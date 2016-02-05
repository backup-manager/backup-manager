<?php namespace BackupManager\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RestoreCommand extends ConsoleCommand {

    protected function configure() {
        $this
            ->setName('restore')
            ->setDescription('Restore a database dump on a service.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

    }
}