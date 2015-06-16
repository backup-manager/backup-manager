<?php

namespace spec\BackupManager\Compressors;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NullCompressorSpec extends ObjectBehavior {

    function it_is_initializable() {
        $this->shouldHaveType('BackupManager\Compressors\NullCompressor');
    }

    function it_should_recognize_its_type_with_case_insensitivity() {
        foreach (['null', 'NULL', 'NUll'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    function it_should_return_empty_commands() {
        $this->getCompressCommandLine('foo')->shouldBe('');
        $this->getDecompressCommandLine('foo')->shouldBe('');
    }

    function it_should_not_modify_paths() {
        $this->getCompressedPath('foo')->shouldBe('foo');
        $this->getCompressedPath('bar')->shouldBe('bar');
    }
}
