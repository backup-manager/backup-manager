<?php namespace BackupManager\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends ConsoleCommand {

    protected function configure() {
        $this
            ->setName('init')
            ->setDescription('Initialize the Backup Manager configuration.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

    }
}