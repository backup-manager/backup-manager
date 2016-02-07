<?php namespace BackupManager\Console;

use BackupManager\Backup;
use BackupManager\File;
use BackupManager\RemoteFile;
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
                new InputOption('database', null, InputOption::VALUE_OPTIONAL, null),
                new InputOption('compressor', null, InputOption::VALUE_OPTIONAL, null),
            ]);
    }

    protected function handle() {
        // Database
        $connections = $this->mapDatabaseConnections($this->config()->get('databases.connections'));
        $database = $this->choiceQuestion('Which database do you want to dump?', $connections, $this->config()->get('databases.default'));
        $this->lineBreak();

        // Storage provider
        $providers = $this->mapFilesystemProviders($this->config()->get('storage.providers'));
        $provider = $this->choiceQuestion('On which storage provider do you want to store this dump?', $providers, $this->config()->get('storage.default'));
        $this->lineBreak();

        $this->output()->writeln("<question>And what path?</question>");
        $remoteFilePath = $this->askInput();
        $this->lineBreak();

        $compress = $this->confirmation('Do you want to compress this dump?', false);
        $this->lineBreak();

        if ($compress) {
            $compression = $this->choiceQuestion('With what?', ['null' => 'Null', 'gzip' => 'Gzip'], 'null');
            $this->lineBreak();
        }

        $compressionText = $compress ? "and compress it to [{$compression}]" : "without compression";
        $confirmation = $this->confirmation("To be sure, you want to backup [{$database}], store it on [{$provider}] at [{$remoteFilePath}], {$compressionText}?");
        if ($confirmation)
            $this->performBackup($database, $provider, $remoteFilePath, $compression);

        $this->lineBreak();
        $this->output()->writeln('Failed to run backup.');
        exit;
    }

    private function mapDatabaseConnections($connections) {
        $mapped = [];
        foreach ($connections as $key => $connection) {
            $driver = $connection['driver'] == 'mysql' ? 'MySQL' : 'PostgreSQL';
            $mapped[$key] = "{$key} ({$driver})";
        }
        return $mapped;
    }

    private function mapFilesystemProviders($providers) {
        $mapped = [];
        foreach ($providers as $key => $provider) {
            $driver = ucfirst($provider['driver']);
            $mapped[$key] = "{$key} ({$driver})";
        }
        return $mapped;
    }

    private function performBackup($database, $provider, $remoteFilePath, $compression) {
        $remoteFiles = [new RemoteFile($provider, new File($remoteFilePath))];
        $backup = new Backup(
            $this->databaseFactory()->make($database),
            $this->filesystem(),
            $this->compressorFactory()->make($compression)
        );
        $backup->run($remoteFiles);
        $this->info('Successfully created database dump.');
        exit;
    }
}