<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Tests\Procedures;

use Fezfez\BackupManager\Compressors\Compressor;
use Fezfez\BackupManager\Databases\Database;
use Fezfez\BackupManager\Filesystems\BackupManagerFilesystemAdapter;
use Fezfez\BackupManager\Filesystems\BackupManagerRessource;
use Fezfez\BackupManager\Filesystems\Destination;
use Fezfez\BackupManager\Filesystems\LocalFilesystemAdapter;
use Fezfez\BackupManager\Procedures\Backup;
use Fezfez\BackupManager\ShellProcessing\ShellProcessor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

final class BackupTest extends TestCase
{
    public function testOk(): void
    {
        $shellProcessor = $this->createMock(ShellProcessor::class);
        $local          = $this->createMock(LocalFilesystemAdapter::class);
        $to             = $this->createMock(BackupManagerFilesystemAdapter::class);
        $database       = $this->createMock(Database::class);
        $compressor     = $this->createMock(Compressor::class);
        $ressource      = $this->createMock(BackupManagerRessource::class);
        $destination    = $this->createMock(Destination::class);

        $sUT = new Backup($shellProcessor);

        $database->expects(self::once())->method('getDumpDataCommandLine')->with(self::anything())->willReturn('a script');
        $database->expects(self::once())->method('getDumpStructCommandLine')->with(self::anything())->willReturn('a script');
        $shellProcessor->expects(self::exactly(2))->method('__invoke')->with(self::callback(static function (Process $process) {
            return $process->getCommandLine() === 'a script';
        }));
        $compressor->expects(self::once())->method('compress')->with(self::anything())->willReturn('my compressd path');
        $destination->expects(self::once())->method('destinationFilesystem')->willReturn($to);
        $destination->expects(self::once())->method('destinationPath')->willReturn('my dest path');
        $to->expects(self::once())->method('writeStream')->with('my dest path', $ressource);
        $local->expects(self::once())->method('readStream')->with('my compressd path')->willReturn($ressource);
        $local->expects(self::once())->method('delete')->with('my compressd path');
        $local->expects(self::once())->method('getRootPath')->willReturn('/myrootpath/');

        $sUT->__invoke($local, $database, [$destination], $compressor);
    }
}
