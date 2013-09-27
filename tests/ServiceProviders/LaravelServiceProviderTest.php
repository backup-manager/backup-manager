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