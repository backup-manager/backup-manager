<?php

namespace spec\BackupManager\Compressors;

use BackupManager\Compressors\Compressor;
use BackupManager\Compressors\NullCompressor;
use BackupManager\File;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NullCompressorSpec extends ObjectBehavior {

    function it_is_initializable() {
        $this->shouldHaveType(NullCompressor::class);
        $this->shouldImplement(Compressor::class);
    }

    function it_should_compress_the_file() {
        $file = new File('file.sql');
        $this->compress($file)->shouldReturn(null);
    }

    function it_should_decompress_the_file() {
        $file = new File('file.sql');
        $this->decompress($file)->shouldReturn(null);
    }

    function it_retrieves_the_compressed_file() {
        $file = new File('file.sql');
        $compressedFile = $this->compressedFile($file);
        $compressedFile->shouldBeAnInstanceOf(File::class);
        $compressedFile->fullPath()->shouldReturn('file.sql');
    }

    function it_retrieves_the_decompressed_file() {
        $file = new File('file.sql');
        $decompressedFile = $this->decompressedFile($file);
        $decompressedFile->shouldBeAnInstanceOf(File::class);
        $decompressedFile->fullPath()->shouldReturn('file.sql');
    }
}
