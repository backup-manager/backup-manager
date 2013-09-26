<?php namespace McCool\DatabaseBackup\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Config;

class BackupCommand extends Command {

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
    protected $description = 'Backup the database, optionally to S3.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $dumper   = $this->getDumper();
        $archiver = $this->getArchiver();
        $storer   = $this->getStorer();

        $backup = new \McCool\DatabaseBackup\BackupProcedure($dumper, $archiver, $storer);

        $backup->backup();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('database', null, InputOption::VALUE_OPTIONAL, 'The database connection to backup, uses the default if not specified.', null),
            array('local-path', null, InputOption::VALUE_OPTIONAL, 'The local storage path for the dump. Defaults to app/storage/dumps', null),
            array('s3-bucket', null, InputOption::VALUE_OPTIONAL, 'Specify this option to upload to S3.', null),
            array('s3-path', null, InputOption::VALUE_OPTIONAL, 'Define the path on the S3 bucket to store the file.', null),
            array('gzip', null, InputOption::VALUE_NONE, 'Gzip the backup.', null),
            array('cleanup', null, InputOption::VALUE_NONE, 'Remove the dump when the process finishes.', null),
        );
    }

    private function getDumper()
    {
        // configure dumper
        $connections = Config::get('database.connections');
        $connection = $this->option('database') ?: Config::get('database.default');
        $conn = $connections[$connection];

        $localPath = $this->option('local-path') ?: storage_path() . '/dumps';
        $filename = $conn['database'] .'-'. date('Y-m-d_H-i-s') . '.sql';
        $filePath = $localPath . '/'.$filename;

        $processor = new \McCool\DatabaseBackup\Processors\ShellProcessor;

        return new \McCool\DatabaseBackup\Dumpers\MysqlDumper($processor, $conn['host'], 3306, $conn['username'], $conn['password'], $conn['database'], $filePath);
    }

    private function getArchiver()
    {
        if ($this->option('gzip')) {
            $processor = new \McCool\DatabaseBackup\Processors\ShellProcessor;

            return new \McCool\DatabaseBackup\Archivers\GzipArchiver($processor);
        }

        return null;
    }

    private function getStorer()
    {
        if ($this->option('s3-bucket')) {
            return new \McCool\DatabaseBackup\Storers\S3Storer($this->option('s3-bucket'), $this->option('s3-path'));
        }

        return null;
    }
}