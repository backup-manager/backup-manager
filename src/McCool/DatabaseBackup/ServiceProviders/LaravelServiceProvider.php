<?php namespace McCool\DatabaseBackup\ServiceProviders;

use Illuminate\Support\ServiceProvider;
use McCool\DatabaseBackup\Commands\LaravelBackupCommand;

class LaravelServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->package('mccool/database-backup');
    }

    public function register()
    {
        $this->app['databasebackup.backupcommand'] = $this->app->share(function($app) {
            return new LaravelBackupCommand();
        });

        $this->commands('databasebackup.backupcommand');
    }
}