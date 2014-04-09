<?php

use BigName\DatabaseBackup\ShellProcessing\ShellProcessor;
use Mockery as m;
use Symfony\Component\Process\Process;

class ShellProcessorTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $this->assertInstanceOf('BigName\DatabaseBackup\ShellProcessing\ShellProcessor', new ShellProcessor(new Process('')));
    }

    public function test_can_process()
    {
        $process = m::mock('Symfony\Component\Process\Process');
        $process->shouldReceive('setCommandLine')->with('foo')->once();
        $process->shouldReceive('isSuccessful')->andReturn(true);
        $process->shouldIgnoreMissing();

        /** @noinspection PhpParamsInspection */
        $shell = new ShellProcessor($process);
        $shell->process('foo');
    }

    public function test_shell_process_failed_exception()
    {
        $this->setExpectedException('BigName\DatabaseBackup\ShellProcessing\ShellProcessFailed');

        $process = m::mock('Symfony\Component\Process\Process');
        $process->shouldReceive('isSuccessful')->andReturn(false);
        $process->shouldIgnoreMissing();

        /** @noinspection PhpParamsInspection */
        $shell = new ShellProcessor($process);
        $shell->process('foo');
    }

    public function test_shell_processing_doesnt_occur_with_empty_string()
    {
        $process = m::mock('Symfony\Component\Process\Process');
        $process->shouldReceive('setCommandLine')->never();
        $process->shouldIgnoreMissing();

        /** @noinspection PhpParamsInspection */
        $shell = new ShellProcessor($process);
        $shell->process('');
    }
}
