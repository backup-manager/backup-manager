<?php namespace BackupManager\Console;

use BackupManager\Compressors\CompressorFactory;
use BackupManager\Config\Config;
use BackupManager\Config\ConfigReaderFactory;
use BackupManager\Config\ConfigReaderTypeDoesNotExist;
use BackupManager\Databases\DatabaseFactory;
use BackupManager\File;
use BackupManager\Shell\ShellProcessor;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

abstract class ConfigurationDependentCommand extends Command {

    /** @var Config */
    private $config;
    /** @var ShellProcessor */
    private $shell;
    /** @var CompressorFactory */
    private $compressorFactory;
    /** @var DatabaseFactory */
    private $databaseFactory;

    protected function execute(InputInterface $input, OutputInterface $output) {
        if ( ! $file = $this->configurationFile())
            throw new CouldNotFindConfiguration;

        $this->parseConfiguration($file);
        $this->makeShellProcessor();
        $this->makeCompressorFactory();
        $this->makeDatabaseFactory();

        parent::execute($input, $output);
    }

    private function configurationFile() {
        $paths = ['backupmanager.yml', 'backupmanager.yml.dist'];
        if ($this->input()->getOption('config'))
            array_unshift($paths, $this->input()->getOption('config'));
        foreach ($paths as $path)
            if (file_exists(getcwd() . "/{$path}"))
                return new File($path, getcwd());

        return false;
    }

    /**
     * @param File $file
     * @throws ConfigReaderTypeDoesNotExist
     */
    private function parseConfiguration(File $file) {
        $readerFactory = new ConfigReaderFactory;
        $reader = $readerFactory->make($file->extension());
        $this->config = $reader->read($file);
    }

    /**
     * @return Config
     */
    protected function config() {
        return $this->config;
    }

    protected function compressorFactory() {
        return $this->compressorFactory;
    }

    /**
     * @return ShellProcessor
     */
    private function makeShellProcessor() {
        $this->shell = new ShellProcessor(new Process('', null, null, null, null));
    }

    private function makeCompressorFactory() {
        $this->compressorFactory = new CompressorFactory($this->shell);
    }

    private function makeDatabaseFactory() {
        $this->databaseFactory = new DatabaseFactory($this->shell, $this->config);
    }
}