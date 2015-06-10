<?php

namespace spec\BackupManager\Tasks\Compression;

use BackupManager\Compressors\Compressor;
use BackupManager\ShellProcessing\ShellProcessor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CompressFileSpec extends ObjectBehavior {

    function it_is_initializable(Compressor $compressor, ShellProcessor $shellProcessor) {
        $this->beConstructedWith($compressor, 'path', $shellProcessor);
        $this->shouldHaveType('BackupManager\Tasks\Compression\CompressFile');
    }

    function it_should_execute_the_compression_command(Compressor $compressor, ShellProcessor $shellProcessor) {
        $compressor->getCompressCommandLine('path')->willReturn('compress path');
        $shellProcessor->process('compress path')->shouldBeCalled();

        $this->beConstructedWith($compressor, 'path', $shellProcessor);
        $this->execute();
    }
}
