<?php

namespace spec\BackupManager\Compressors;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GzipCompressorSpec extends ObjectBehavior {

    function it_is_initializable() {
        $this->shouldHaveType('BackupManager\Compressors\GzipCompressor');
    }

    function it_should_recognize_its_type_with_case_insensitivity() {
        foreach (['gzip', 'GZIP', 'GZip'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    function it_should_generate_valid_compression_commands() {
        $this->getCompressCommandLine('foo')->shouldBe("gzip 'foo'");
        $this->getCompressCommandLine('../foo')->shouldBe("gzip '../foo'");
        $this->getCompressCommandLine('../foo.sql')->shouldBe("gzip '../foo.sql'");
    }

    function it_should_generate_valid_decompression_commands() {
        $this->getDecompressCommandLine('foo')->shouldBe("gzip -d 'foo'");
        $this->getDecompressCommandLine('../foo.gz')->shouldBe("gzip -d '../foo.gz'");
        $this->getDecompressCommandLine('../foo.sql.gz')->shouldBe("gzip -d '../foo.sql.gz'");
    }

    function it_should_generate_compressed_paths_from_filenames() {
        $this->getCompressedPath('a')->shouldBe('a.gz');
        $this->getCompressedPath('/a')->shouldBe('/a.gz');
        $this->getCompressedPath('/a.sql')->shouldBe('/a.sql.gz');
    }

    function it_should_generate_decompressed_paths_from_filenames() {
        $this->getDecompressedPath('a.gz')->shouldBe('a');
        $this->getDecompressedPath('/a.gz')->shouldBe('/a');
        $this->getDecompressedPath('/a.sql.gz')->shouldBe('/a.sql');
    }
}
