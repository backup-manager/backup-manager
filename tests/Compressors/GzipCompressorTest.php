<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Tests\Compressors;

use Fezfez\BackupManager\Compressors\GzipCompressor;
use Fezfez\BackupManager\ShellProcessing\ShellProcessor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

final class GzipCompressorTest extends TestCase
{
    /** @return iterable<int, array<string>> */
    public static function provideCompress(): iterable
    {
        yield ['foo', "gzip 'foo'", 'foo.gz'];
        yield ['../foo', "gzip '../foo'", '../foo.gz'];
        yield ['../foo.sql', "gzip '../foo.sql'", '../foo.sql.gz'];
    }

    /** @dataProvider  provideCompress */
    public function testCompress(string $actual, string $expected, string $path): void
    {
        $shellProcessor = $this->createMock(ShellProcessor::class);
        $shellProcessor->expects(self::once())->method('__invoke')->with(self::callback(static function (Process $process) use ($expected) {
            return $expected === $process->getCommandLine();
        }));

        $sUT = new GzipCompressor($shellProcessor);
        self::assertSame($path, $sUT->compress($actual));
    }

    /** @return iterable<int, array<string>> */
    public static function provideDecompress(): iterable
    {
        yield ['foo.gz', "gzip -d 'foo.gz'", 'foo'];
        yield ['../foo.gz', "gzip -d '../foo.gz'", '../foo'];
        yield ['../foo.sql.gz', "gzip -d '../foo.sql.gz'", '../foo.sql'];
    }

    /** @dataProvider provideDecompress  */
    public function testGenerateValidDecompressionCommands(string $actual, string $expected, string $path): void
    {
        $shellProcessor = $this->createMock(ShellProcessor::class);
        $shellProcessor->expects(self::once())->method('__invoke')->with(self::callback(static function (Process $process) use ($expected) {
            return $expected === $process->getCommandLine();
        }));

        $sUT = new GzipCompressor($shellProcessor);
        self::assertSame($path, $sUT->decompress($actual));
    }
}
