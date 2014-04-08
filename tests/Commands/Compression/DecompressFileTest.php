<?php

use BigName\DatabaseBackup\Commands\Compression\DecompressFile;
use Mockery as m;

class DecompressFileTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $compressor = m::mock('BigName\DatabaseBackup\Compressors\Compressor');
        $shell = m::mock('BigName\DatabaseBackup\ShellProcessing\ShellProcessor');
        $command = new DecompressFile($compressor, 'foo', $shell);
        $this->assertInstanceOf('BigName\DatabaseBackup\Commands\Compression\DecompressFile', $command);
    }

    public function test_generates_correct_command()
    {
        $compressor = m::mock('BigName\DatabaseBackup\Compressors\Compressor');
        $compressor->shouldReceive('getDecompressCommandLine')->andReturn('foo');

        $shell = m::mock('BigName\DatabaseBackup\ShellProcessing\ShellProcessor');
        $shell->shouldReceive('process')->with('foo')->once();

        $command = new DecompressFile($compressor, 'bar', $shell);
        $command->execute();
    }
}
