<?php

use McCool\DatabaseBackup\Processors\ShellProcessor;
use Mockery as m;

class ShellProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testShellProcessor()
    {
        // for some reason a regular mock is crashing my php5-cli
        $process = m::mock('alias:Symfony\Component\Process\Process');
        $process->shouldReceive('setCommandLine')->with('test command');
        $process->shouldReceive('run');
        $process->shouldReceive('getErrorOutput')->andReturn('no errors');

        $processor = new ShellProcessor($process);
        $processor->process('test command');

        $this->assertEquals('no errors', $processor->getErrors());
    }
}