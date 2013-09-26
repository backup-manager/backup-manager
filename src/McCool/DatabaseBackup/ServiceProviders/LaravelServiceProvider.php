<?php namespace McCool\DatabaseBackup\ServiceProviders;

use Illuminate\Support\ServiceProvider;
use McCool\DatabaseBackup\Commands\BackupCommand;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $dbConfig = $this->app['config']->get('database');

        $this->app['db.backup'] = $this->app->share(function($app) use ($dbConfig) {
            return new BackupCommand($dbConfig);
        });

        $this->commands('db.backup');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

}