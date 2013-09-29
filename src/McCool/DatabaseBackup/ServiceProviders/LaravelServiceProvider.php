<?php namespace McCool\DatabaseBackup\ServiceProviders;

use Illuminate\Support\ServiceProvider;

use McCool\DatabaseBackup\Commands\LaravelBackupCommand;
use McCool\DatabaseBackup\Dumpers\MysqlDumper;
use McCool\DatabaseBackup\Archivers\GzipArchiver;
use McCool\DatabaseBackup\Processors\ShellProcessor;
use McCool\DatabaseBackup\Storers\S3Storer;

use Symfony\Component\Process\Process;
use Aws\Common\Aws;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $path = realpath( $this->guessPackagePath() . '/..' );
        $this->package('mccool/database-backup', 'database-backup', $path);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('databasebackup.backupcommand', function($app) {
            return new LaravelBackupCommand();
        });
        $this->commands('databasebackup.backupcommand');

        $this->app->bind('databasebackup.s3client', function($app) {
            return Aws::factory([
                'key'    => $app['config']->get('database-backup::aws.key'),
                'secret' => $app['config']->get('database-backup::aws.secret'),
                'region' => $app['config']->get('database-backup::aws.region'),
            ])->get('s3');
        });

        $this->app->bind('databasebackup.archivers.gziparchiver', function($app) {
            return new GzipArchiver(new ShellProcessor(new Process('')));
        });

        $this->app->bind('databasebackup.processors.shellprocessor', function($app) {
            return new ShellProcessor(new Process(''));
        });

        $this->app->bind('databasebackup.storers.s3storer', function($app, $params) {
            return new S3Storer($app->make('databasebackup.s3client'), $params['s3-bucket'], $params['s3-path']);
        });

        $this->app->bind('databasebackup.dumpers.mysqldumper', function($app, $params) {
            return new MysqlDumper($app['databasebackup.processors.shellprocessor'], $params['host'], $params['port'], $params['username'], $params['password'], $params['database'], $params['filePath']);
        });
    }
}