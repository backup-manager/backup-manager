<?php

namespace spec\BigName\BackupManager\Compressors;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NullCompressorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('BigName\BackupManager\Compressors\Compressor');
    }

    function it_should_recognize_its_type_with_case_insensitivity()
    {
        foreach (['null', 'NULL', 'NUll'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
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
