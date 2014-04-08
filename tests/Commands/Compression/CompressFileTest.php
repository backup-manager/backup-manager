<?php

use BigName\DatabaseBackup\Commands\Compression\CompressFile;
use Mockery as m;

class CompressFileTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $shell = m::mock('BigName\DatabaseBackup\ShellProcessing\ShellProcessor');
        $compressor = m::mock('BigName\DatabaseBackup\Compressors\Compressor');
        $command = new CompressFile($compressor, 'foo', $shell);
        $this->assertInstanceOf('BigName\DatabaseBackup\Commands\Compression\CompressFile', $command);
    }

    public function test_generates_correct_command()
    {
        $compressor = m::mock('BigName\DatabaseBackup\Compressors\Compressor');
        $compressor->shouldReceive('getCompressCommandLine')->andReturn('foo');

        $shell = m::mock('BigName\DatabaseBackup\ShellProcessing\ShellProcessor');
        $shell->shouldReceive('process')->with('foo')->once();

        $command = new CompressFile($compressor, 'foo', $shell);
        $command->execute();
    }
}
