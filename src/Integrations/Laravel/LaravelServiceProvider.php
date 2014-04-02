<?php namespace BigName\DatabaseBackup\Frameworks\Laravel;

use Illuminate\Support\ServiceProvider;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     * @return void
     */
    public function boot()
    {
        $this->package('bigname/database-backup', 'database-backup', __DIR__.'/../../..');
    }

    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {

    }
}
