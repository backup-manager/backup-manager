<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Tests\Procedures;

use Fezfez\BackupManager\Compressors\Compressor;
use Fezfez\BackupManager\Databases\Database;
use Fezfez\BackupManager\Filesystems\BackupManagerFilesystemAdapter;
use Fezfez\BackupManager\Filesystems\BackupManagerRessource;
use Fezfez\BackupManager\Filesystems\LocalFilesystemAdapter;
use Fezfez\BackupManager\Procedures\Restore;
use Fezfez\BackupManager\ShellProcessing\ShellProcessor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

final class RestoreTest extends TestCase
{
    public function testOk(): void
    {
        $shellProcessor = $this->createMock(ShellProcessor::class);
        $local          = $this->createMock(LocalFilesystemAdapter::class);
        $to             = $this->createMock(BackupManagerFilesystemAdapter::class);
        $database       = $this->createMock(Database::class);
        $compressor     = $this->createMock(Compressor::class);
        $ressource      = $this->createMock(BackupManagerRessource::class);

        $sUT = new Restore($shellProcessor);

        $local->expects(self::once())->method('writeStream')->with(self::anything(), $ressource);
        $to->expects(self::once())->method('readStream')->with('toto')->willReturn($ressource);
        $compressor->expects(self::once())->method('decompress')->with(self::anything())->willReturn('toto');
        $database->expects(self::once())->method('getRestoreCommandLine')->with('toto')->willReturn('a script');
        $shellProcessor->expects(self::once())->method('__invoke')->with(self::callback(static function (Process $process) {
            return $process->getCommandLine() === 'a script';
        }));
        $local->expects(self::once())->method('delete')->with('toto');

        $sUT->__invoke($local, $to, 'toto', $database, $compressor);
    }
}
