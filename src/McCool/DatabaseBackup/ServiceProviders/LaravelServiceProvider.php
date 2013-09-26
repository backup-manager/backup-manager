<?php namespace McCool\DatabaseBackup\ServiceProviders;

use Illuminate\Support\ServiceProvider;
use McCool\DatabaseBackup\Commands\LaravelBackupCommand;

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
        $this->app['databasebackup.backupcommand'] = $this->app->share(function($app) {
            return new LaravelBackupCommand();
        });

        $this->commands('databasebackup.backupcommand');
    }
}