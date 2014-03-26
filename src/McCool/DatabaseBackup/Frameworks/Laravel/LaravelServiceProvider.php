<?php namespace McCool\DatabaseBackup\Frameworks\Laravel;

use Illuminate\Support\ServiceProvider;

use McCool\DatabaseBackup\Mysql\Mysql;
use McCool\DatabaseBackup\Gzip\Gzip;
use McCool\DatabaseBackup\Mysql\MysqlConnectionDetails;
use McCool\DatabaseBackup\S3\S3;
use McCool\DatabaseBackup\Shell\ShellProcessor;
use Symfony\Component\Process\Process;
use Aws\Common\Aws;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     * @return void
     */
    public function boot()
    {
        $path = realpath($this->guessPackagePath() . '/..');
        $this->package('mccool/database-backup', 'database-backup', $path);
    }

    /**
     * Register the service provider.
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

        $this->app->bind('databasebackup.gzip', function($app) {
            return new Gzip(new ShellProcessor(new Process('')));
        });

        $this->app->bind('databasebackup.processors.shellprocessor', function($app) {
            return new ShellProcessor(new Process(''));
        });

        $this->app->bind('databasebackup.s3', function($app, $params) {
            return new S3($app->make('databasebackup.s3client'), $params['s3-bucket'], $params['s3-path']);
        });

        $this->app->bind('databasebackup.mysql', function($app, $params) {

            return new Mysql($app['databasebackup.shellprocessor'], $params['mysqlConnectionDetails']);
        });
    }
}
