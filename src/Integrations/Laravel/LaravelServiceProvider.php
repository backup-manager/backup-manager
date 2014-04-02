<?php namespace BigName\DatabaseBackup\Frameworks\Laravel;

use Illuminate\Support\ServiceProvider;

use BigName\DatabaseBackup\Mysql\Mysql;
use BigName\DatabaseBackup\Gzip\Gzip;
use BigName\DatabaseBackup\Mysql\MysqlConnectionDetails;
use BigName\DatabaseBackup\S3\S3;
use BigName\DatabaseBackup\CommandProcessor;
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
