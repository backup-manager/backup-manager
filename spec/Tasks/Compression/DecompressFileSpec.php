<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Tests\Tasks\Compression;

use Fezfez\BackupManager\Compressors\Compressor;
use Fezfez\BackupManager\ShellProcessing\ShellProcessor;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class DecompressFileSpec extends TestCase
{
    public function testExecuteTheDecompressionCommand(Compressor $compressor, ShellProcessor $shellProcessor): void
    {
        $compressor->getDecompressCommandLine('path')->willReturn('decompress path');
        $shellProcessor->process(Argument::any())->shouldBeCalled();

        $this->beConstructedWith($compressor, 'path', $shellProcessor);
        $this->execute();
    }
}
