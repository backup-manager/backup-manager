<?php namespace BigName\BackupManager\Integrations\Laravel; 

use BigName\BackupManager\Commands\Storage\ListDirectoryContents;
use BigName\BackupManager\Databases\DatabaseProvider;
use BigName\BackupManager\Filesystems\FilesystemProvider;
use BigName\BackupManager\Procedures\RestoreProcedure;
use Symfony\Component\Console\Input\InputOption;

class ManagerRestoreCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'manager:restore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore a database dump from a service';

    /**
     * The required arguments.
     *
     * @var array
     */
    private $required = ['source', 'sourcePath', 'database', 'compression'];

    /**
     * The forgotten arguments.
     *
     * @var array
     */
    private $forgotten;

    /**
     * @var \BigName\BackupManager\Procedures\RestoreProcedure
     */
    private $restore;

    /**
     * @var \BigName\BackupManager\Filesystems\FilesystemProvider
     */
    private $filesystems;

    /**
     * @var \BigName\BackupManager\Databases\DatabaseProvider
     */
    private $databases;

    /**
     * @param \BigName\BackupManager\Procedures\RestoreProcedure $restore
     * @param \BigName\BackupManager\Filesystems\FilesystemProvider $filesystems
     * @param \BigName\BackupManager\Databases\DatabaseProvider $databases
     */
    public function __construct(RestoreProcedure $restore, FilesystemProvider $filesystems, DatabaseProvider $databases)
    {
        $this->restore = $restore;
        $this->filesystems = $filesystems;
        $this->databases = $databases;

        parent::__construct();
    }

    /**
     *
     */
    public function fire()
    {
        $this->info('Starting backup process...'.PHP_EOL);
        if ($this->hasForgotten()) {
            $this->listForgotten();
            $this->askRemainingArguments();
        }

        $this->restore->run(
            $this->option('source'),
            $this->option('sourcePath'),
            $this->option('database'),
            $this->option('compression')
        );

        $message = sprintf('Backup from service "%s" at "%s" has been successfully restored to "%s".',
            $this->option('source'),
            $this->option('sourcePath'),
            $this->option('database')
        );
        $this->info(PHP_EOL.$message);
    }

    /**
     * @return bool
     */
    private function hasForgotten()
    {
        foreach ($this->required as $argument) {
            if ( ! $this->option($argument)) {
                $this->forgotten[] = $argument;
            }
        }
        return isset($this->forgotten);
    }

    /**
     * @return void
     */
    private function listForgotten()
    {
        $this->info("These arguments haven't been filled yet:");
        $this->line(implode(', ', $this->forgotten));
        $this->info('The following questions will fill these in for you.');
        $this->line('');
    }

    /**
     * @return void
     */
    private function askRemainingArguments()
    {
        foreach ($this->forgotten as $argument) {
            $method = 'ask'.ucfirst($argument);
            $this->{$method}();
        }
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function askSource()
    {
        $this->info('Available storage services:');
        $providers = $this->filesystems->getAvailableProviders();
        $this->line(implode(', ', $providers));
        $source = $this->autocomplete('From which storage service do you want to choose?', $providers);
        $this->line('');
        $this->input->setOption('source', $source);
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function askSourcePath()
    {
        // ask path
        $path = $this->ask('From which path do you want to select?');
        $this->line('');

        // ask file
        $this->info('Available database dumps:');
        $command = new ListDirectoryContents(
            $this->filesystems->get($this->option('source')),
            $path
        );
        $files = array_map(function($file) {
            return $file['basename'];
        }, $command->execute());
        $this->line(implode(PHP_EOL, $files));
        $filename = $this->autocomplete('Which database dump do you want to restore?', $files);

        $this->input->setOption('sourcePath', "{$path}/{$filename}");
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function askDatabase()
    {
        $this->info('Available database connections:');
        $providers = $this->databases->getAvailableProviders();
        $this->line(implode(', ', $providers));
        $database = $this->autocomplete('From which database connection you want to dump?', $providers);
        $this->line('');
        $this->input->setOption('database', $database);
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function askCompression()
    {
        $this->info('Available compression types:');
        $types = ['gzip', 'null'];
        $this->line(implode(', ', $types));
        $compression = $this->autocomplete('Which compression type you want to use?', $types, 'null');
        $this->line('');
        $this->input->setOption('compression', $compression);
    }

    /**
     * @return void
     */
    private function validateArguments()
    {
        $this->info("You've filled in the following answers:");
        $this->line("Source: <comment>{$this->option('source')}</comment>");
        $this->line("Source Path: <comment>{$this->option('sourcePath')}</comment>");
        $this->line("Compression: <comment>{$this->option('compression')}</comment>");
        $this->line("Source: <comment>{$this->option('source')}</comment>");
        $this->line('');
        $confirmation = $this->confirm('Are these correct? [y/n]');
        if ( ! $confirmation) {
            $this->reaskArguments();
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['source', null, InputOption::VALUE_OPTIONAL, 'Source configuration name', null],
            ['sourcePath', null, InputOption::VALUE_OPTIONAL, 'Source path from service', null],
            ['database', null, InputOption::VALUE_OPTIONAL, 'Database configuration name', null],
            ['compression', null, InputOption::VALUE_OPTIONAL, 'Compression type', null],
        ];
    }
}
