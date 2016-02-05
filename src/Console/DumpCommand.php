<?php namespace BackupManager\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DumpCommand extends ConsoleCommand {

    protected function configure() {
        $this
            ->setName('dump')
            ->setDescription('Create database dump and save it on a service');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
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

        $database = $this->choiceQuestion($input, $output, 'Which database do you want to dump?', $databases['connections'], $databases['default']);
        $this->lineBreak($output);

        $provider = $this->choiceQuestion($input, $output, 'On which storage provider do you want to store this dump?', $storage['providers'], $storage['default']);
        $this->lineBreak($output);

        $output->writeln("<question>And what path?</question>");
        $remoteFilePath = $this->askInput($input, $output);
        $this->lineBreak($output);

        $compress = $this->confirmation($input, $output, 'Do you want to compress this dump?', false);
        $this->lineBreak($output);

        if ($compress) {
            $compression = $this->choiceQuestion($input, $output, 'With what?', ['gzip' => 'Gzip'], 'gzip');
            $this->lineBreak($output);
        }

        $compressionText = $compress ? "and compress it to [{$compression}]" : "without compression";
        $confirmation = $this->confirmation($input, $output, "To be sure, you want to backup [{$database}], store it on [{$provider}] at [{$remoteFilePath}], {$compressionText}?");
        if ($confirmation)
            return compact('database', 'provider', 'remoteFilePath', 'compress');

        $this->lineBreak($output);
        $output->writeln('Failed to run backup.');
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