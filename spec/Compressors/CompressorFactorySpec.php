<?php

namespace spec\BackupManager\Compressors;

use BackupManager\Compressors\CompressorFactory;
use BackupManager\Compressors\GzipCompressor;
use BackupManager\Compressors\NullCompressor;
use BackupManager\Shell\ShellProcessor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CompressorFactorySpec extends ObjectBehavior {

    function let(ShellProcessor $shell) {
        $this->beConstructedWith($shell);
    }

    function it_is_initializable() {
        $this->shouldHaveType(CompressorFactory::class);
    }

    function it_can_make_a_gzip_compressor() {
        $this->make('gzip')->shouldBeAnInstanceOf(GzipCompressor::class);
    }

    function it_can_make_a_null_compressor() {
        $this->make('null')->shouldBeAnInstanceOf(NullCompressor::class);
    }
}
