<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Tests;

use Fezfez\BackupManager\BackupManager;
use Fezfez\BackupManager\Compressors\Compressor;
use Fezfez\BackupManager\Databases\Database;
use Fezfez\BackupManager\Filesystems\BackupManagerFilesystemAdapter;
use Fezfez\BackupManager\Filesystems\Destination;
use Fezfez\BackupManager\Filesystems\LocalFilesystemAdapter;
use Fezfez\BackupManager\Procedures\BackupProcedure;
use Fezfez\BackupManager\Procedures\RestoreProcedure;
use PHPUnit\Framework\TestCase;

final class BackupManagerTest extends TestCase
{
    public function testCreateABackupProcedure(): void
    {
        $backupProcedure  = $this->createMock(BackupProcedure::class);
        $restoreProcedure = $this->createMock(RestoreProcedure::class);
        $destination      = $this->createMock(Destination::class);
        $local            = $this->createMock(LocalFilesystemAdapter::class);
        $to               = $this->createMock(BackupManagerFilesystemAdapter::class);
        $database         = $this->createMock(Database::class);
        $compressor       = $this->createMock(Compressor::class);

        $sUT = new BackupManager($backupProcedure, $restoreProcedure);

        $backupProcedure->expects(self::once())->method('__invoke')->with($local, $database, [$destination], 'tutu', $compressor);
        $restoreProcedure->expects(self::once())->method('__invoke')->with($local, $to, 'toto', $database, $compressor);

        $sUT->backup($local, $database, [$destination], 'tutu', $compressor);
        $sUT->restore($local, $to, 'toto', $database, $compressor);
    }
}
