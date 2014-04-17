<?php  namespace BigName\BackupManager\Integrations\Laravel;

use Symfony\Component\Console\Input\InputOption;
use BigName\BackupManager\Filesystems\FilesystemProvider;

class ManagerListCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'manager:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List directory from a path';

    /**
     * @var \BigName\BackupManager\Filesystems\FilesystemProvider
     */
    private $filesystems;

    /**
     * The required arguments.
     *
     * @var array
     */
    private $required = ['source', 'path'];

    /**
     * The forgotten arguments.
     *
     * @var array
     */
    private $forgotten;

    public function __construct(FilesystemProvider $filesystems)
    {
        $this->filesystems = $filesystems;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws \LogicException
     * @throws \BigName\BackupManager\Filesystems\FilesystemTypeNotSupported
     * @throws \BigName\BackupManager\Config\ConfigFieldNotFound
     * @throws \BigName\BackupManager\Config\ConfigNotFoundForConnection
     * @return void
     */
    public function fire()
    {
        $this->info('Starting list process...'.PHP_EOL);
        if ($this->hasForgotten()) {
            $this->listForgotten();
            $this->askRemainingArguments();
        }
        $this->validateArguments();

        $filesystem = $this->filesystems->get($this->option('source'));
        $contents = $filesystem->listContents($this->option('path'));
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
        $this->table(['Name', 'Extension', 'Size', 'Created'], $rows);
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
        $this->info('The following questions will fill these in for you.'.PHP_EOL);
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
        $this->info('Available sources:');
        $providers = $this->filesystems->getAvailableProviders();
        $this->line(implode(', ', $providers));
        $default = current($providers);
        $source = $this->autocomplete("From which source do you want to list? [{$default}]", $providers, $default);
        $this->line('');
        $this->input->setOption('source', $source);
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function askPath()
    {
        $path = $this->ask('From which path? [/]', '/');
        $this->line('');
        $this->input->setOption('path', $path);
    }

    /**
     * @return void
     */
    private function validateArguments()
    {
        $this->info("You've filled in the following answers:");
        $this->line("Source: <comment>{$this->option('source')}</comment>");
        $this->line("Path: <comment>{$this->option('path')}</comment>");
        $this->line('');
        $confirmation = $this->confirm('Are these correct? [y/n]');
        $this->line('');
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
            ['path', null, InputOption::VALUE_OPTIONAL, 'Directory path', null],
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
