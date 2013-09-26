<?php namespace Mccool\LaravelArtisanBackup;

use Illuminate\Support\ServiceProvider;

class LaravelArtisanBackupServiceProvider extends ServiceProvider
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
			return new Commands\BackupCommand($dbConfig);
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