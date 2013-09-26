<?php namespace McCool\DatabaseBackup\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Config;

use McCool\DatabaseBackup\BackupProcedure;
use McCool\DatabaseBackup\Processors\ShellProcessor;
use McCool\DatabaseBackup\Dumpers\MysqlDumper;
use McCool\DatabaseBackup\Archivers\GzipArchiver;
use McCool\DatabaseBackup\Storers\S3Storer;

class LaravelBackupCommand extends Command
{
    protected $name = 'db:backup';
    protected $description = 'Backup the database, optionally to S3.';

    public function fire()
    {
        $dumper   = $this->getDumper();
        $archiver = $this->getArchiver();
        $storer   = $this->getStorer();

        $backup = new BackupProcedure($dumper, $archiver, $storer);

        $backup->backup();
    }

    protected function getArguments()
    {
        return array();
    }

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

        $processor = new ShellProcessor;

        return new MysqlDumper($processor, $conn['host'], 3306, $conn['username'], $conn['password'], $conn['database'], $filePath);
    }

    private function getArchiver()
    {
        if ($this->option('gzip')) {
            $processor = new ShellProcessor;

            return new GzipArchiver($processor);
        }

        return null;
    }

    private function getStorer()
    {
        if ($this->option('s3-bucket')) {
            $awsKey    = Config::get('package::aws.key');
            $awsSecret = Config::get('package::aws.secret');
            $awsRegion = Config::get('package::aws.region');

            return new S3Storer($awsKey, $awsSecret, $awsRegion, $this->option('s3-bucket'), $this->option('s3-path'));
        }

        return null;
    }
}