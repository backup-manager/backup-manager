<?php

use BigName\BackupManager\Compressors\CompressorProvider;
use BigName\BackupManager\Compressors\GzipCompressor;
use BigName\BackupManager\Config\Config;
use Mockery as m;

class CompressorProviderTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $provider = new CompressorProvider;
        $this->assertInstanceOf('BigName\BackupManager\Compressors\CompressorProvider', $provider);
    }

    public function test_can_create_compressor()
    {
        $provider = new CompressorProvider;
        $provider->add(new GzipCompressor);

        $compressor = $provider->get('gzip');
        $this->assertInstanceOf('BigName\BackupManager\Compressors\GzipCompressor', $compressor);
    }

    public function test_unsupported_database_exception()
    {
        $this->setExpectedException('BigName\BackupManager\Compressors\CompressorTypeNotSupported');
        $provider = new CompressorProvider;
        $provider->add(new GzipCompressor);
        $provider->get('unsupported');
    }
}
