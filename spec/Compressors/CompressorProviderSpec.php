<?php

namespace spec\BackupManager\Compressors;

use BackupManager\Compressors\GzipCompressor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CompressorProviderSpec extends ObjectBehavior {

    function it_is_initializable() {
        $this->shouldHaveType('BackupManager\Compressors\CompressorProvider');
    }

    function it_should_provide_compressors_by_name() {
        $this->add(new GzipCompressor);
        $this->get('gzip')->shouldHaveType('BackupManager\Compressors\GzipCompressor');
    }

    function it_should_throw_an_exception_if_it_cant_find_a_compressor() {
        $this->shouldThrow('BackupManager\Compressors\CompressorTypeNotSupported')->during('get', ['unsupported']);
    }
}
