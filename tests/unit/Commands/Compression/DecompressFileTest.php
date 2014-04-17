<?php

use BigName\BackupManager\Commands\Compression\DecompressFile;
use Mockery as m;

class DecompressFileTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $compressor = m::mock('BigName\BackupManager\Compressors\Compressor');
        $shell = m::mock('BigName\BackupManager\ShellProcessing\ShellProcessor');
        $command = new DecompressFile($compressor, 'foo', $shell);
        $this->assertInstanceOf('BigName\BackupManager\Commands\Compression\DecompressFile', $command);
    }

    public function test_generates_correct_command()
    {
        $compressor = m::mock('BigName\BackupManager\Compressors\Compressor');
        $compressor->shouldReceive('getDecompressCommandLine')->andReturn('foo');

        $shell = m::mock('BigName\BackupManager\ShellProcessing\ShellProcessor');
        $shell->shouldReceive('process')->with('foo')->once();

        $command = new DecompressFile($compressor, 'bar', $shell);
        $command->execute();
    }
}
