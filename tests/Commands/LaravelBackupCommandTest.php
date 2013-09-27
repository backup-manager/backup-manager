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
        // @todo - move mysqldumper stuff into the service provider so that I can mock it out
        $this->runCommand();
    }

    private function runCommand()
    {
        $command = $this->getCommand();

        $input = m::mock('Symfony\Component\Console\Input\InputInterface');
        $input->shouldReceive('bind', 'isInteractive', 'validate');
        $input->shouldReceive('getOption');

        $output = m::mock('Symfony\Component\Console\Output\OutputInterface');

        $command->run($input, $output);
    }

    private function getCommand()
    {
        date_default_timezone_set('UTC');

        $helperSet = new Symfony\Component\Console\Helper\HelperSet;
        $definition = new Symfony\Component\Console\Input\InputDefinition;

        $consoleApplication = m::mock('Symfony\Component\Console\Application');
        $consoleApplication->shouldReceive('getHelperSet')->andReturn($helperSet);
        $consoleApplication->shouldReceive('getDefinition')->andReturn($definition);

        $command = new LaravelBackupCommand;
        $command->setLaravel($this->getApp());
        $command->setApplication($consoleApplication);

        return $command;
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
        $app['path.storage'] = "storage path";

        $provider = new LaravelServiceProvider($app);
        $provider->register();

        return $app;
    }

    private function getContainer()
    {
        return new Container;
    }
}