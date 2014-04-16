<?php

use BigName\BackupManager\Compressors\NullCompressor;
use Mockery as m;

class NullCompressorTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $this->assertInstanceOf('BigName\BackupManager\Compressors\NullCompressor', new NullCompressor);
    }

    public function test_handles_correct_types()
    {
        $comp = new NullCompressor;

        foreach (['null', 'NULL', 'NUll'] as $type) {
            $this->assertTrue($comp->handles($type));
        }

        foreach ([null, 'foo'] as $type) {
            $this->assertFalse($comp->handles($type));
        }
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
