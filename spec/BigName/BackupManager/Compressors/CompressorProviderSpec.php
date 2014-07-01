<?php

namespace spec\BigName\BackupManager\Compressors;

use BigName\BackupManager\Compressors\GzipCompressor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CompressorProviderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('BigName\BackupManager\Compressors\CompressorProvider');
    }

    function it_should_provide_compressors_by_name()
    {
        $this->add(new GzipCompressor);
        $this->get('gzip')->shouldHaveType('BigName\BackupManager\Compressors\GzipCompressor');
    }

    function it_should_throw_an_exception_if_it_cant_find_a_compressor()
    {
        $this->shouldThrow('BigName\BackupManager\Compressors\CompressorTypeNotSupported')->during('get', ['unsupported']);
    }
}
