<?php namespace BigName\BackupManager\Integrations\Laravel;

use BigName\BackupManager\Databases\DatabaseProvider;
use BigName\BackupManager\Filesystems\FilesystemProvider;
use BigName\BackupManager\Procedures\BackupProcedure;
use Symfony\Component\Console\Input\InputOption;

class ManagerBackupCommand extends BaseCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'manager:backup';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Backs up stuff';

    /**
     * The required arguments.
     *
     * @var array
     */
    private $required = ['database', 'destination', 'destinationPath', 'compression'];

    /**
     * The forgotten arguments.
     *
     * @var array
     */
    private $forgotten;

    /**
     * @var BigName\BackupManager\Procedures\BackupProcedure
     */
    private $backupProcedure;

    /**
     * @var BigName\BackupManager\Databases\DatabaseProvider
     */
    private $databaseProvider;

    /**
     * @var BigName\BackupManager\Filesystems\FilesystemProvider
     */
    private $filesystemProvider;

    public function __construct(BackupProcedure $backupProcedure, DatabaseProvider $databaseProvider, FilesystemProvider $filesystemProvider)
    {
        $this->backupProcedure = $backupProcedure;
        $this->databaseProvider = $databaseProvider;
        $this->filesystemProvider = $filesystemProvider;

        parent::__construct();
    }

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $this->info('Starting backup process...'.PHP_EOL);
        if ($this->hasForgottenArguments()) {
            $this->listForgottenArguments();
            $this->askForForgottenArguments();
        }
        $this->askForArgumentValidation();

        $this->backupProcedure->run(
            $this->option('database'),
            $this->option('destination'),
            $this->option('destinationPath'),
            $this->option('compression')
        );

        $message = sprintf('Backup from connection "%s" has been successfully saved to "%s" on "%s"',
            $this->option('database'),
            $this->option('destination'),
            $this->option('destinationPath')
        );
        $this->info(PHP_EOL.$message);
	}

    private function hasForgottenArguments()
    {
        foreach ($this->required as $argument) {
            if ( ! $this->option($argument)) {
                $this->forgotten[] = $argument;
            }
        }
        return isset($this->forgotten);
    }

    private function listForgottenArguments()
    {
        $this->info("These arguments haven't been filled yet:");
        $this->line(implode(', ', $this->forgotten));
        $this->info('The following questions will fill these in for you.'.PHP_EOL);
    }

    private function askForForgottenArguments()
    {
        foreach ($this->forgotten as $argument) {
            $method = 'ask'.ucfirst($argument);
            $this->{$method}();
        }
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function askDatabase()
    {
        $this->info('Available database connections:');
        $providers = $this->databaseProvider->getAvailableProviders();
        $this->line(implode(', ', $providers));
        $database = $this->askAndValidate('From which database connection you want to dump?', $providers);
        $this->line('');
        $this->input->setOption('database', $database);
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function askDestination()
    {
        $this->info('Available storage services:');
        $providers = $this->filesystemProvider->getAvailableProviders();
        $this->line(implode(', ', $providers));
        $destination = $this->askAndValidate('To which storage service you want to save?', $providers);
        $this->line('');
        $this->input->setOption('destination', $destination);
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function askDestinationPath()
    {
        $path = $this->ask('On which path you want to save?');
        $this->line('');
        $this->input->setOption('destinationPath', $path);
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function askCompression()
    {
        $this->info('Available compression types:');
        $types = ['gzip', 'null'];
        $this->line(implode(', ', $types));
        $compression = $this->askAndValidate('Which compression type you want to use?', $types, 'null');
        $this->line('');
        $this->input->setOption('compression', $compression);
    }

    private function askForArgumentValidation()
    {
        $this->info("You've filled in the following answers:");
        $this->line("Database: <comment>{$this->option('database')}</comment>");
        $this->line("Destination: <comment>{$this->option('destination')}</comment>");
        $this->line("Destination Path: <comment>{$this->option('destinationPath')}</comment>");
        $this->line("Compression: <comment>{$this->option('compression')}</comment>");
        $this->line('');
        $confirmation = $this->confirm('Are these correct? [y/n]');
        if ( ! $confirmation) {
            $this->reaskArguments();
        }
    }

    private function reaskArguments()
    {
        $this->line('');
        $this->info('Answers have been reset and re-asking questions.');
        $this->line('');
        $this->askForForgottenArguments();
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
