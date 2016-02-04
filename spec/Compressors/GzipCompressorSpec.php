<?php

namespace spec\BackupManager\Compressors;

use BackupManager\Compressors\Compressor;
use BackupManager\Compressors\GzipCompressor;
use BackupManager\File;
use BackupManager\Shell\ShellCommand;
use BackupManager\Shell\ShellProcessor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GzipCompressorSpec extends ObjectBehavior {

    function let(ShellProcessor $shell) {
        $this->beConstructedWith($shell);
    }

    function it_is_initializable() {
        $this->shouldHaveType(GzipCompressor::class);
        $this->shouldImplement(Compressor::class);
    }

    function it_should_compress_the_file(ShellProcessor $shell) {
        $shell->process(new ShellCommand("gzip 'file.sql'"))->shouldBeCalled();
        $this->beConstructedWith($shell);

        $file = new File('file.sql');
        $this->compress($file);
    }

    function it_should_decompress_the_file(ShellProcessor $shell) {
        $shell->process(new ShellCommand("gunzip 'file.sql'"))->shouldBeCalled();
        $this->beConstructedWith($shell);

        $file = new File('file.sql');
        $this->decompress($file);
    }

    function it_retrieves_the_compressed_file() {
        $file = new File('file.sql');
        $compressedFile = $this->compressedFile($file);
        $compressedFile->shouldBeAnInstanceOf(File::class);
        $compressedFile->fullPath()->shouldReturn('file.sql.gz');
    }

    function it_retrieves_the_decompressed_file() {
        $file = new File('file.sql.gz');
        $compressedFile = $this->decompressedFile($file);
        $compressedFile->shouldBeAnInstanceOf(File::class);
        $compressedFile->fullPath()->shouldReturn('file.sql');
    }
}
