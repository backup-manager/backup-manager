<?php

use BigName\DatabaseBackup\Commands\Archiving\GunzipFile;
use Mockery as m;

class GunzipFileTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $shell = m::mock('BigName\DatabaseBackup\ShellProcessing\ShellProcessor');
        $gunzip = new GunzipFile('foo', $shell);
        $this->assertInstanceOf('BigName\DatabaseBackup\Commands\Archiving\GunzipFile', $gunzip);
    }

    public function test_generates_correct_command()
    {
        $shell = m::mock('BigName\DatabaseBackup\ShellProcessing\ShellProcessor');
        $shell->shouldReceive('process')->with("gunzip 'foo'");

        $gunzip = new GunzipFile('foo', $shell);
        $gunzip->execute();
    }
}
