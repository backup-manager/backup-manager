<?php

use BigName\DatabaseBackup\Compressors\NullCompressor;
use Mockery as m;

class NullCompressorTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $this->assertInstanceOf('BigName\DatabaseBackup\Compressors\NullCompressor', new NullCompressor);
    }

    public function test_command_lines_are_empty()
    {
        $null = new NullCompressor;
        $this->assertEquals('', $null->getCompressCommandLine('filename'));
        $this->assertEquals('', $null->getDecompressCommandLine('filename'));
    }

    public function test_paths_remain_unmodified()
    {
        $null = new NullCompressor;
        $this->assertEquals('filename', $null->getCompressedPath('filename'));
        $this->assertEquals('filename', $null->getDecompressedPath('filename'));
    }
}
