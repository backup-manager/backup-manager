<?php

use BigName\DatabaseBackup\Compressors\GzipCompressor;
use Mockery as m;

class GzipCompressorTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $gzip = new GzipCompressor;
        $this->assertInstanceOf('BigName\DatabaseBackup\Compressors\GzipCompressor', $gzip);
    }

    public function test_can_generate_compress_command_line()
    {
        $gzip = new GzipCompressor;
        $this->assertEquals("gzip 'foo'", $gzip->getCompressCommandLine('foo'));
        $this->assertEquals("gzip '../bar'", $gzip->getCompressCommandLine('../bar'));
        $this->assertEquals("gzip '../bar.sql'", $gzip->getCompressCommandLine('../bar.sql'));
    }

    public function test_can_generate_decompress_command_line()
    {
        $gzip = new GzipCompressor;
        $this->assertEquals("gunzip 'foo.gz'", $gzip->getDecompressCommandLine('foo.gz'));
        $this->assertEquals("gunzip '../bar.gz'", $gzip->getDecompressCommandLine('../bar.gz'));
        $this->assertEquals("gunzip '../bar.sql.gz'", $gzip->getDecompressCommandLine('../bar.sql.gz'));
    }


    public function test_can_get_compressed_path()
    {
        $gzip = new GzipCompressor;
        $this->assertEquals('a.gz', $gzip->getCompressedPath('a'));
        $this->assertEquals('/a.gz', $gzip->getCompressedPath('/a'));
        $this->assertEquals('/a.sql.gz', $gzip->getCompressedPath('/a.sql'));
    }

    public function test_can_get_decompressed_path()
    {
        $gzip = new GzipCompressor();
        $this->assertEquals('a', $gzip->getDecompressedPath('a.gz'));
        $this->assertEquals('/a', $gzip->getDecompressedPath('/a.gz'));
        $this->assertEquals('/a.sql', $gzip->getDecompressedPath('/a.sql.gz'));
    }
}
