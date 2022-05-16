<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Tests\Tasks\Compression;

use Fezfez\BackupManager\Compressors\Compressor;
use Fezfez\BackupManager\ShellProcessing\ShellProcessor;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class CompressFileSpec extends TestCase
{
    public function testExecuteTheCompressionCommand(Compressor $compressor, ShellProcessor $shellProcessor): void
    {
        $compressor->getCompressCommandLine('path')->willReturn('compress path');

        $shellProcessor->process(Argument::any())->shouldBeCalled();

        $this->beConstructedWith($compressor, 'path', $shellProcessor);
        $this->execute();
    }
}
