<?php

namespace spec\BigName\BackupManager\Tasks\Compression;

use BigName\BackupManager\Compressors\Compressor;
use BigName\BackupManager\ShellProcessing\ShellProcessor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DecompressFileSpec extends ObjectBehavior
{
    function it_is_initializable(Compressor $compressor, ShellProcessor $shellProcessor)
    {
        $this->beConstructedWith($compressor, 'path', $shellProcessor);
        $this->shouldHaveType('BigName\BackupManager\Tasks\Compression\DecompressFile');
    }

    function it_should_execute_the_compressor_command(Compressor $compressor, ShellProcessor $shellProcessor)
    {
        $compressor->getDecompressCommandLine('foo')->willReturn('decompress foo');
        $shellProcessor->process('decompress foo')->shouldBeCalled();

        $this->beConstructedWith($compressor, 'foo', $shellProcessor);
        $this->execute();
    }
}
