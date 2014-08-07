<?php

namespace spec\BigName\BackupManager\Compressors;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NullCompressorSpec extends ObjectBehavior
{
    function it_is_an_implementation_of_compressor()
    {
        $this->shouldHaveType('BigName\BackupManager\Compressors\Compressor');
    }

    function it_recognizes_the_correct_type()
    {
        foreach (['null', 'NULL', 'NUll'] as $type) {
            $this->handles($type)->shouldBeTrue();
        }
    }

    function it_recognizes_the_incorrect_type()
    {
        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBeFalse();
        }
    }

    function it_returns_null_as_the_shell_compress_command()
    {
        $this->getCompressCommandLine('foo')->shouldBeNull();
    }

    function it_returns_null_as_the_shell_decompress_command()
    {
        $this->getDecompressCommandLine('foo')->shouldBeNull();
    }

    function it_returns_null_as_the_compressed_path()
    {
        $this->getCompressedPath('compressed/path')->shouldBeNull();
    }

    function it_returns_null_as_the_decompressed_path()
    {
        $this->getCompressedPath('decompressed/path')->shouldBeNull();
    }
}
