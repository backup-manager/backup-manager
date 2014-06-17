<?php  namespace BigName\BackupManager\Integrations\Laravel;

use Symfony\Component\Console\Input\InputOption;
use BigName\BackupManager\Filesystems\FilesystemProvider;

class DbListCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'db:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List contents of a backup storage destination.';

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
     * The missing arguments.
     *
     * @var array
     */
    private $missingArguments;


    public function __construct(FilesystemProvider $filesystems)
    {
        parent::__construct();
        $this->filesystems = $filesystems;
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
        if ($this->isMissingArguments()) {
            $this->displayMissingArguments();
            $this->promptForMissingArgumentValues();
            $this->validateArguments();
        }

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
        $this->info("These arguments haven't been filled yet: <comment>{$formatted}</comment>.");
        $this->info('The following questions will fill these in for you.');
        $this->line('');
    }

    /**
     * @return void
     */
    private function promptForMissingArgumentValues()
    {
        foreach ($this->missingArguments as $argument) {
            if ($argument == 'source') {
                $this->askSource();
            } elseif ($argument == 'path') {
                $this->askPath();
            }
            $this->line('');
        }
    }

    private function askSource()
    {
        $providers = $this->filesystems->getAvailableProviders();
        $formatted = implode(', ', $providers);
        $this->info("Available sources: <comment>{$formatted}</comment>");
        $source = $this->autocomplete("From which source do you want to list?", $providers);
        $this->input->setOption('source', $source);
    }

    private function askPath()
    {
        $root = $this->filesystems->getConfig($this->option('source'), 'root');
        $path = $this->ask("From which path?<comment> {$root}</comment>");
        $this->input->setOption('path', $path);
    }

    /**
     * @return void
     */
    private function validateArguments()
    {
        $root = $this->filesystems->getConfig($this->option('source'), 'root');
        $this->info('Just to be sure...');
        $this->info(sprintf('Do you want to list files from <comment>%s</comment> on <comment>%s</comment>?',
            $root.$this->option('path'),
            $this->option('source')
        ));
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
