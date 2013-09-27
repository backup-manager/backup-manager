<?php

use Illuminate\Container\Container;
use McCool\DatabaseBackup\ServiceProviders\LaravelServiceProvider;
use Mockery as m;

class LaravelServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testCanCreate()
    {
        $provider = new LaravelServiceProvider($this->getContainer());

        $this->assertInstanceOf('McCool\DatabaseBackup\ServiceProviders\LaravelServiceProvider', $provider);
    }

    public function testCanBoot()
    {
        $app = $this->getApp();

        $provider = new LaravelServiceProvider($app);
        $provider->boot();
    }

    public function testCanRegister()
    {
        $app = $this->getApp();

        $provider = new LaravelServiceProvider($app);
        $provider->register();
    }

    public function testCanCreateBackupCommand()
    {
        $app = $this->getApp();

        $provider = new LaravelServiceProvider($app);
        $provider->register();

        $this->assertInstanceOf('McCool\DatabaseBackup\Commands\LaravelBackupCommand', $app->make('databasebackup.backupcommand'));
    }

    public function testCanCreateS3Client()
    {
        $app = $this->getApp();

        $provider = new LaravelServiceProvider($app);
        $provider->register();

        $this->assertInstanceOf('Aws\S3\S3Client', $app->make('databasebackup.s3client'));
    }

    public function testCanCreateGzipArchiver()
    {
        $app = $this->getApp();

        $provider = new LaravelServiceProvider($app);
        $provider->register();

        $this->assertInstanceOf('McCool\DatabaseBackup\Archivers\GzipArchiver', $app->make('databasebackup.archivers.gziparchiver'));
    }

    public function testCanCreateShellProcessor()
    {
        $app = $this->getApp();

        $provider = new LaravelServiceProvider($app);
        $provider->register();

        $this->assertInstanceOf('McCool\DatabaseBackup\Processors\ShellProcessor', $app->make('databasebackup.processors.shellprocessor'));
    }

    public function testCanCreateS3Storer()
    {
        $app = $this->getApp();

        $provider = new LaravelServiceProvider($app);
        $provider->register();

        $s3Config = ['s3-bucket' => 'bucket', 's3-path' => 'path'];
        $this->assertInstanceOf('McCool\DatabaseBackup\Storers\S3Storer', $app->make('databasebackup.storers.s3storer', $s3Config));
    }

    public function testCanCreateMysqlDumper()
    {
        $app = $this->getApp();

        $provider = new LaravelServiceProvider($app);
        $provider->register();

        $dumperConfig = [
            'host'     => 'bucket',
            'port'     => 3306,
            'username' => 'username',
            'password' => 'password',
            'database' => 'database',
            'filePath' => 'filePath',
        ];

        $this->assertInstanceOf('McCool\DatabaseBackup\Dumpers\MysqlDumper', $app->make('databasebackup.dumpers.mysqldumper', $dumperConfig));
    }

    private function getApp()
    {
        $app = $this->getContainer();

        $app['files'] = m::mock();
        $app['files']->shouldReceive('isDirectory');

        $app['events'] = m::mock();
        $app['events']->shouldReceive('listen');

        $app['config'] = m::mock();
        $app['config']->shouldReceive('get');

        $app['path'] = "path";

        return $app;
    }

    private function getContainer()
    {
        return new Container;
    }
}