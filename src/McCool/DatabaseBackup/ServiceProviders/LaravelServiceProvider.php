<?php namespace McCool\DatabaseBackup\ServiceProviders;

use Illuminate\Support\ServiceProvider;

use McCool\DatabaseBackup\Commands\LaravelBackupCommand;
use McCool\DatabaseBackup\Archivers\GzipArchiver;
use McCool\DatabaseBackup\Processors\ShellProcessor;

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
        $this->package('mccool/database-backup');
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
                'key'    => $app['config']->get('aws.key'),
                'secret' => $app['config']->get('aws.secret'),
                'region' => $app['config']->get('aws.region'),
            ])->get('s3');
        });

        $this->app->bind('databasebackup.archivers.gziparchiver', function($app) {
            return new GzipArchiver(new ShellProcessor(new Process('')));
        });

        $this->app->bind('databasebackup.processors.shellprocessor', function($app) {
            return new ShellProcessor(new Process(''));
        });
    }
}