<?php namespace BackupManager\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends BaseApplication {

    /** @var InputInterface */
    private $input;
    /** @var OutputInterface */
    private $output;

    public function __construct($version) {
        parent::__construct('Backup Manager', $version);
    }

    public function doRun(InputInterface $input, OutputInterface $output) {
        $this->input = $input;
        $this->output = $output;

        $this->add(new InitCommand);
        $this->add(new DumpCommand);
        $this->add(new RestoreCommand);
        $this->add(new CopyCommand);

        parent::doRun($input, $output);
    }

    /**
     * @return InputInterface
     */
    public function input() {
        return $this->input;
    }

    /**
     * @return OutputInterface
     */
    public function output() {
        return $this->output;
    }
}