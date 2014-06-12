<?php namespace BigName\BackupManager\Integrations\Laravel;

use Symfony\Component\Console\Input\InputOption;
use BigName\BackupManager\Databases\DatabaseProvider;
use BigName\BackupManager\Procedures\BackupProcedure;
use BigName\BackupManager\Filesystems\FilesystemProvider;

/**
 * Class ManagerBackupCommand
 * @package BigName\BackupManager\Integrations\Laravel
 */
class DbBackupCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create database dump and save it on a service';

    /**
     * The required arguments.
     *
     * @var array
     */
    private $required = ['database', 'destination', 'destinationPath', 'compression'];

    /**
     * The missing arguments.
     *
     * @var array
     */
    private $missingArguments;

    /**
     * @var \BigName\BackupManager\Procedures\BackupProcedure
     */
    private $backupProcedure;

    /**
     * @var \BigName\BackupManager\Databases\DatabaseProvider
     */
    private $databases;

    /**
     * @var \BigName\BackupManager\Filesystems\FilesystemProvider
     */
    private $filesystems;

    /**
     * @param BackupProcedure $backupProcedure
     * @param DatabaseProvider $databaseProvider
     * @param FilesystemProvider $filesystemProvider
     */
    public function __construct(BackupProcedure $backupProcedure, DatabaseProvider $databases, FilesystemProvider $filesystems)
    {
        $this->backupProcedure = $backupProcedure;
        $this->databases = $databases;
        $this->filesystems = $filesystems;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws \BigName\BackupManager\Filesystems\FilesystemTypeNotSupported
     * @throws \BigName\BackupManager\Compressors\CompressorTypeNotSupported
     * @throws \BigName\BackupManager\Databases\DatabaseTypeNotSupported
     * @throws \BigName\BackupManager\Config\ConfigNotFoundForConnection
     * @return mixed
     */
	public function fire()
	{
        if ($this->isMissingArguments()) {
            $this->displayMissingArguments();
            $this->promptForMissingArgumentValues();
            $this->validateArguments();
        }

        $this->backupProcedure->run(
            $this->option('database'),
            $this->option('destination'),
            $this->option('destinationPath'),
            $this->option('compression')
        );

        $message = sprintf('Backup from connection "%s" has been successfully saved to "%s" on "%s%s"',
            $this->option('database'),
            $this->option('destination'),
            $this->filesystemProvider->getConfig($this->option('destination'), 'root'),
            $this->option('destinationPath')
        );
        $this->line('');
        $this->info($message);
	}

    /**
     * @return bool
     */
    private function isMissingArguments()
    {
        foreach ($this->required as $argument) {
            if ( ! $this->option($argument)) {
                $this->missingArguments[] = $argument;
            }
        }
        return isset($this->missingArguments);
    }

    /**
     * @return void
     */
    private function displayMissingArguments()
    {
        $formatted = implode(', ', $this->missingArguments);
        $this->info("These arguments haven't been filled yet: <comment>{$formatted}</comment>");
        $this->info('The following questions will fill these in for you.');
        $this->line('');
    }

    /**
     * @return void
     */
    private function promptForMissingArgumentValues()
    {
        foreach ($this->missingArguments as $argument) {
            if ($argument == 'database') {
                $this->askDatabase();
            } elseif ($argument == 'destination') {
                $this->askDestination();
            } elseif ($argument == 'destinationPath') {
                $this->askDestinationPath();
            } elseif ($argument == 'compression') {
                $this->askCompression();
            }
            $this->line('');
        }
    }

    private function askDatabase()
    {
        $providers = $this->databases->getAvailableProviders();
        $formatted = implode(', ', $providers);
        $this->info("Available database connections: <comment>{$formatted}</comment>");
        $database = $this->autocomplete("From which database connection you want to dump?", $providers);
        $this->input->setOption('database', $database);
    }

    private function askDestination()
    {
        $providers = $this->filesystems->getAvailableProviders();
        $formatted = implode(', ', $providers);
        $this->info("Available storage services: <comment>{$formatted}</comment>");
        $destination = $this->autocomplete("To which storage service you want to save?", $providers);
        $this->input->setOption('destination', $destination);
    }

    private function askDestinationPath()
    {
        $root = $this->filesystems->getConfig($this->option('source'), 'root');
        $path = $this->ask("How do you want to name the backup?<comment> {$root}</comment>");
        $this->input->setOption('destinationPath', $path);
    }

    private function askCompression()
    {
        $types = ['null', 'gzip'];
        $formatted = implode(', ', $types);
        $this->info("Available compression types: <comment>{$formatted}</comment>");
        $compression = $this->autocomplete('Which compression type you want to use?', $types);
        $this->input->setOption('compression', $compression);
    }

    /**
     * @throws \BigName\BackupManager\Config\ConfigFieldNotFound
     * @throws \BigName\BackupManager\Config\ConfigNotFoundForConnection
     * @return void
     */
    private function validateArguments()
    {
        $root = $this->filesystems->getConfig($this->option('destination'), 'root');
        $this->info("You've filled in the following answers:");
        $this->line("Database: <comment>{$this->option('database')}</comment>");
        $this->line("Destination: <comment>{$this->option('destination')}</comment>");
        $this->line("Destination Path: <comment>{$root}{$this->option('destinationPath')}</comment>");
        $this->line("Compression: <comment>{$this->option('compression')}</comment>");
        $this->line('');
        $confirmation = $this->confirm('Are these correct? [Y/n]');
        if ( ! $confirmation) {
            $this->reaskArguments();
        }
    }

    /**
     * Get the console command options.
     *
     * @return void
     */
    private function reaskArguments()
    {
        $this->line('');
        $this->info('Answers have been reset and re-asking questions.');
        $this->line('');
        $this->promptForMissingArgumentValues();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['database', null, InputOption::VALUE_OPTIONAL, 'Database configuration name', null],
            ['destination', null, InputOption::VALUE_OPTIONAL, 'Destination configuration name', null],
            ['destinationPath', null, InputOption::VALUE_OPTIONAL, 'File destination path', null],
            ['compression', null, InputOption::VALUE_OPTIONAL, 'Compression type', null],
		];
	}
}
