<?php namespace BigName\BackupManager\Integrations\Laravel;

use Symfony\Component\Console\Input\InputOption;
use BigName\BackupManager\Databases\DatabaseProvider;
use BigName\BackupManager\Procedures\RestoreProcedure;
use BigName\BackupManager\Filesystems\FilesystemProvider;

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
        $this->validateArguments();

        $this->restore->run(
            $this->option('source'),
            $this->option('sourcePath'),
            $this->option('database'),
            $this->option('compression')
        );

        $message = sprintf('Backup "%s" from service "%s" has been successfully restored to "%s".',
            $this->option('source'),
            basename($this->option('sourcePath')),
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
        $default = current($providers);
        $source = $this->autocomplete("From which storage service do you want to choose? [{$default}]", $providers, $default);
        $this->line('');
        $this->input->setOption('source', $source);
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function askSourcePath()
    {
        // ask path
        $path = $this->ask("From which path do you want to select? [/]", '/');
        $this->line('');

        // ask file
        $filesystem = $this->filesystems->get($this->option('source'));
        $contents = $filesystem->listContents($path);

        $files = [];
        foreach ($contents as $file) {
            if ($file['type'] == 'dir') continue;
            $files[] = $file['basename'];
        }
        if (empty($files)) {
            $this->info('No backups were found at this path.');
            exit;
        }
        $rows = [];
        foreach ($contents as $file) {
            if ($file['type'] == 'dir') continue;
            $rows[] = [
                $file['basename'],
                $file['extension'],
                $this->formatBytes($file['size']),
                date('D j Y  H:i:s', $file['timestamp'])
            ];
        }
        $this->info('Available database dumps:');
        $this->table(['Name', 'Extension', 'Size', 'Created'], $rows);
        $this->line('');
        $filename = $this->autocomplete("Which database dump do you want to restore?", $files);

        $this->input->setOption('sourcePath', "{$path}/{$filename}");
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function askDatabase()
    {
        $this->info('Available database connections:');
        $providers = $this->databases->getAvailableProviders();
        $this->line(implode(', ', $providers));
        $default = current($providers);
        $database = $this->autocomplete("From which database connection you want to dump? [{$default}]", $providers, $default);
        $this->line('');
        $this->input->setOption('database', $database);
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function askCompression()
    {
        $this->info('Available compression types:');
        $types = ['null', 'gzip'];
        $this->line(implode(', ', $types));
        $compression = $this->autocomplete('Which compression type you want to use? [null]', $types, 'null');
        $this->line('');
        $this->input->setOption('compression', $compression);
    }

    /**
     * @return void
     */
    private function validateArguments()
    {
        $dump = basename($this->option('sourcePath'));
        $this->info("You've filled in the following answers:");
        $this->line("Source: <comment>{$this->option('source')}</comment>");
        $this->line("Databse Dump: <comment>{$dump}</comment>");
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
     * @return void
     */
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
            ['source', null, InputOption::VALUE_OPTIONAL, 'Source configuration name', null],
            ['sourcePath', null, InputOption::VALUE_OPTIONAL, 'Source path from service', null],
            ['database', null, InputOption::VALUE_OPTIONAL, 'Database configuration name', null],
            ['compression', null, InputOption::VALUE_OPTIONAL, 'Compression type', null],
        ];
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
