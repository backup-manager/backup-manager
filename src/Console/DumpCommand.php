<?php namespace BackupManager\Console;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DumpCommand extends ConfigurationDependentCommand {

    protected function configure() {
        $this
            ->setName('dump')
            ->setDescription('Create database dump and save it on a service')
            ->setDefinition([
                new InputArgument('procedure', InputArgument::OPTIONAL, null),
                new InputOption('config', null, InputOption::VALUE_OPTIONAL, null),
                new InputOption('database', null, InputOption::VALUE_OPTIONAL, null)
            ]);
    }

    protected function handle() {
        $databases = [
            'default' => 'master',
            'connections' => [
                'master' => '(MySQL)'
            ]
        ];
        $storage = [
            'default' => 'master',
            'providers' => [
                'master' => '(MySQL)'
            ]
        ];

        $database = $this->choiceQuestion('Which database do you want to dump?', $databases['connections'], $databases['default']);
        $this->lineBreak();

        $provider = $this->choiceQuestion('On which storage provider do you want to store this dump?', $storage['providers'], $storage['default']);
        $this->lineBreak();

        $this->output()->writeln("<question>And what path?</question>");
        $remoteFilePath = $this->askInput();
        $this->lineBreak();

        $compress = $this->confirmation('Do you want to compress this dump?', false);
        $this->lineBreak();

        if ($compress) {
            $compression = $this->choiceQuestion('With what?', ['gzip' => 'Gzip'], 'gzip');
            $this->lineBreak();
        }

        $compressionText = $compress ? "and compress it to [{$compression}]" : "without compression";
        $confirmation = $this->confirmation("To be sure, you want to backup [{$database}], store it on [{$provider}] at [{$remoteFilePath}], {$compressionText}?");
        if ($confirmation)
            return compact('database', 'provider', 'remoteFilePath', 'compress');

        $this->lineBreak();
        $this->output()->writeln('Failed to run backup.');
        exit;
    }

    private function structureDatabases($config) {
        $connections = [];
        foreach ($config['connections'] as $key => $connection) {
            $driver = $connection['driver'] == 'mysql' ? 'MySQL' : 'PostgreSQL';
            $connections[$key] = "({$driver})";
        }
        return [
            'default' => $config['default'],
            'connections' => $connections
        ];
    }

    private function structureFilesystems($config) {
        $providers = [];
        foreach ($config['disks'] as $key => $provider) {
            $driver = ucfirst($provider['driver']);
            $providers[$key] = "({$driver})";
        }
        return [
            'default' => $config['default'],
            'providers' => $providers
        ];
    }
}