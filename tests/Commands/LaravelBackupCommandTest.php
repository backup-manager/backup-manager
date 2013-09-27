<?php

use Illuminate\Container\Container;
use McCool\DatabaseBackup\Commands\LaravelBackupCommand;
use McCool\DatabaseBackup\ServiceProviders\LaravelServiceProvider;
use Mockery as m;

class LaravelBackupCommandTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testCanCreate()
    {
        $command = $this->getCommand();

        $this->assertInstanceOf('McCool\DatabaseBackup\Commands\LaravelBackupCommand', $command);
    }

    public function testCanDump()
    {
        $app = $this->getApp();

        // ensure that dumper is called
        $dumper = m::mock('McCool\DatabaseBackup\Dumpers\MysqlDumper');
        $dumper->shouldReceive('getOutputFilename');
        $dumper->shouldReceive('dump')->once();
        $app['databasebackup.dumpers.mysqldumper'] = $dumper;

        // prepare
        $command = $this->getCommand();
        $command->setLaravel($app);

        // run
        $this->runCommand($command);
    }

    public function testCanChooseDbConfig()
    {

    }

    public function testGetDefaultDbConfig()
    {

    }

    public function testCanChangeLocalPath()
    {

    }

    public function testCanArchive()
    {
        $app = $this->getApp();

        // ensure that dumper is called
        $dumper = m::mock('McCool\DatabaseBackup\Dumpers\DumperInterface');
        $dumper->shouldReceive('getOutputFilename');
        $dumper->shouldReceive('dump')->once();
        $app['databasebackup.dumpers.mysqldumper'] = $dumper;

        // ensure that archiver is called
        $archiver = m::mock('McCool\DatabaseBackup\Archivers\ArchiverInterface');
        $archiver->shouldReceive('getOutputFilename', 'setInputFilename');
        $archiver->shouldReceive('archive')->once();
        $app['databasebackup.archivers.gziparchiver'] = $archiver;

        // prepare
        $command = $this->getCommand();
        $command->setLaravel($app);

        // run
        $this->runCommand($command, ['gzip' => true]);
    }

    public function testCanStore()
    {
        $app = $this->getApp();

        // ensure that dumper is called
        $dumper = m::mock('McCool\DatabaseBackup\Dumpers\DumperInterface');
        $dumper->shouldReceive('getOutputFilename');
        $dumper->shouldReceive('dump')->once();
        $app['databasebackup.dumpers.mysqldumper'] = $dumper;

        // ensure that storer is called
        $storer = m::mock('McCool\DatabaseBackup\Storers\StorerInterface');
        $storer->shouldReceive('getOutputFilename', 'setInputFilename');
        $storer->shouldReceive('store')->once();
        $app['databasebackup.storers.s3storer'] = $storer;

        // prepare
        $command = $this->getCommand();
        $command->setLaravel($app);

        // run
        $this->runCommand($command, ['s3-bucket' => 'bucket', 's3-path' => 'path']);
    }

    public function testCanCleanup()
    {
    }

    private function runCommand($command, $options = [])
    {
        $input = m::mock('Symfony\Component\Console\Input\InputInterface');
        $input->shouldReceive('bind', 'isInteractive', 'validate');

        $defaultOptions = [
            'database'   => false,
            'local-path' => false,
            's3-bucket'  => false,
            'cleanup'    => false,
            'gzip'       => false,
        ];

        $options = array_merge($defaultOptions, $options);

        foreach ($options as $key => $value) {
            $input->shouldReceive('getOption')->with($key)->andReturn($value);
        }

        $output = m::mock('Symfony\Component\Console\Output\OutputInterface');

        $command->run($input, $output);
    }

    private function getCommand($options = [], $arguments = [])
    {
        date_default_timezone_set('UTC');

        $helperSet = new Symfony\Component\Console\Helper\HelperSet;

        $input = m::mock('Symfony\Component\Console\Input\InputDefinition');
        $input->shouldReceive('getOptions')->andReturn($options);
        $input->shouldReceive('getArguments')->andReturn($arguments);

        $consoleApplication = m::mock('Symfony\Component\Console\Application');
        $consoleApplication->shouldReceive('getHelperSet')->andReturn($helperSet);
        $consoleApplication->shouldReceive('getDefinition')->andReturn($input);

        $command = new LaravelBackupCommand;
        $command->setApplication($consoleApplication);

        return $command;
    }

    private function getApp()
    {
        $app = $this->getContainer();

        // mock out laravel environment configuration
        $app['files'] = m::mock();
        $app['files']->shouldReceive('isDirectory');

        $app['events'] = m::mock();
        $app['events']->shouldReceive('listen');

        $app['config'] = m::mock();
        $app['config']->shouldReceive('get');

        $app['path'] = "path";
        $app['path.storage'] = "storage path";

        // register ioc bindings
        $provider = new LaravelServiceProvider($app);
        $provider->register();

        return $app;
    }

    private function getContainer()
    {
        return new Container;
    }
}