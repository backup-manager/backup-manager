<?php namespace McCool\DatabaseBackup\ServiceProviders;

use Illuminate\Support\ServiceProvider;
use McCool\DatabaseBackup\Commands\LaravelBackupCommand;
use Aws\Common\Aws;
use App, Config;

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
        App::bind('databasebackup.backupcommand', function($app) {
            return new LaravelBackupCommand();
        });
        $this->commands('databasebackup.backupcommand');

        App::bind('databasebackup.s3client', function($app) {
            return Aws::factory([
                'key'    => Config::get('aws.key'),
                'secret' => Config::get('aws.secret'),
                'region' => Config::get('aws.region'),
            ])->get('s3');
        });

    }
}