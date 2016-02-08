<?php namespace BackupManager\Console;

use BackupManager\Backup;
use BackupManager\Config\Config;
use BackupManager\Config\ConfigItemDoesNotExist;
use BackupManager\File;
use BackupManager\Procedure;
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
                new InputOption('config', null, InputOption::VALUE_OPTIONAL, null)
            ]);
    }

    protected function handle() {
        $procedure = $this->filledInProcedure() ? $this->makeProcedure() : $this->askQuestionsAndMakeProcedure();
        $this->performBackup($procedure);
    }

    private function askQuestionsAndMakeProcedure() {
        $connections = $this->mapDatabaseConnections($this->config()->get('databases.connections'));
        $database = $this->choiceQuestion('Which database do you want to dump?', $connections, $this->config()->get('databases.default'));
        $this->lineBreak();

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

        $compressionText = $compress ? "and compress it with [{$compression}]" : "without compression";
        $confirmation = $this->confirmation("To be sure, you want to backup [{$database}], store it on [{$provider}] at [{$remoteFilePath}], {$compressionText}?");
        if ( ! $confirmation) {
            $this->error('Failed to run backup.');
            exit;
        }
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

    private function filledInProcedure() {
        return $this->input()->getArgument('procedure') != null;
    }

    private function makeProcedure() {
        try {
            // Change so if Config returns an array, it'll return another Config instance
            $procedureName = $this->input()->getArgument('procedure');
            $procedureConfig = new Config($this->config()->get("procedures.{$procedureName}"));
        } catch (ConfigItemDoesNotExist $e) {
            $this->error($e->getMessage());
            exit;
        }
        return new Procedure(
            $procedureName,
            $procedureConfig->get('database'),
            $procedureConfig->get('destinations'),
            $procedureConfig->get('compression')
        );
    }

    private function performBackup(Procedure $procedure) {
        $backup = new Backup(
            $this->databaseFactory()->make($procedure->database()),
            $this->filesystem(),
            $this->compressorFactory()->make($procedure->compression())
        );
        $backup->run($procedure->destinations());
        $this->info('Successfully created database dump.');
        exit;
    }
}