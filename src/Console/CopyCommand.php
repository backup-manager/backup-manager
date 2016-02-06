<?php namespace BackupManager\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CopyCommand extends Command {

    protected function configure() {
        $this
            ->setName('copy')
            ->setDescription('Copies a third parties\' configuration to Backup Manager configuration.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

    }
}