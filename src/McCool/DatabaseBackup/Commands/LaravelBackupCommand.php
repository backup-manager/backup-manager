<?php namespace McCool\DatabaseBackup\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use McCool\DatabaseBackup\BackupProcedure;
use McCool\DatabaseBackup\Storers\S3Storer;
use McCool\DatabaseBackup\Dumpers\MysqlDumper;

class LaravelBackupCommand extends Command
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
    protected $description = 'Backup the database, optionally to S3.';

    /**
     * Execure the backup command.
     *
     * @return void
     */
    public function fire()
    {
        $backup = new BackupProcedure($this->getDumper());

        $archiver = $this->getArchiver();
        $storer   = $this->getStorer();

        if ($archiver) {
            $backup->setArchiver($archiver);
        }

        if ($storer) {
            $backup->setStorer($storer);
        }

        $backup->backup();

        if ($this->option('cleanup')) {
            $backup->cleanup();
        }
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
            array('local-path', null, InputOption::VALUE_OPTIONAL, 'The local storage path for the dump. Defaults to app/storage/dumps.', null),
            array('s3-bucket', null, InputOption::VALUE_OPTIONAL, 'Specify this option to upload to S3.', null),
            array('s3-path', null, InputOption::VALUE_OPTIONAL, 'Define the path on the S3 bucket to store the file.', null),
            array('filename', null, InputOption::VALUE_OPTIONAL, 'Define the filename to be used.', null),
            array('gzip', null, InputOption::VALUE_NONE, 'Gzip the backup.', null),
            array('cleanup', null, InputOption::VALUE_NONE, 'Remove the dump when the process finishes.', null),
        );
    }

    /**
     * Returns a MysqlDumper instance.
     *
     * @return \McCool\DatabaseBackup\Dumpers\MysqlDumper
     */
    protected function getDumper()
    {
        // laravel config
        $connections = $this->laravel['config']->get('database.connections');
        $connection = $this->option('database') ?: $this->laravel['config']->get('database.default');
        $conn = $connections[$connection];

        // file path
        $storagePath = $this->laravel['path.storage'];
        $localPath = $this->option('local-path') ?: $storagePath . '/dumps';
        $filename = $this->option('filename') ?: ($conn['database'] .'-'. date('Y-m-d_H-i-s') . '.sql');
        $filePath = $localPath . '/'.$filename;

        // dumper config
        $config = [
            'host'     => $conn['host'],
            'port'     => 3306,
            'username' => $conn['username'],
            'password' => $conn['password'],
            'database' => $conn['database'],
            'filePath' => $filePath,
        ];

        return $this->laravel->make('databasebackup.dumpers.mysqldumper', $config);
    }

    /**
     * Returns the GzipArchiver instance.
     *
     * @return \McCool\DatabaseBackup\Archivers\GzipArchiver|null
     */
    protected function getArchiver()
    {
        if ($this->option('gzip')) {
            return $this->laravel->make('databasebackup.archivers.gziparchiver');
        }

        return null;
    }

    /**
     * Returns the S3Storer instance.
     *
     * return \McCool\DatabaseBackup\Storers\S3Storer|null
     */
    protected function getStorer()
    {
        if ($this->option('s3-bucket')) {
            return $this->laravel->make('databasebackup.storers.s3storer', [
                's3-bucket' => $this->option('s3-bucket'),
                's3-path'   => $this->option('s3-path'),
            ]);
        }

        return null;
    }
}
