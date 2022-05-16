<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Tests\Filesystems;

use Fezfez\BackupManager\Filesystems\BackupManagerFilesystemAdapter;
use Fezfez\BackupManager\Filesystems\Destination;
use PHPUnit\Framework\TestCase;

final class DestinationTest extends TestCase
{
    public function testOk(): void
    {
        $backupManagerFilesystemAdapter = $this->createMock(BackupManagerFilesystemAdapter::class);

        $sUT = new Destination($backupManagerFilesystemAdapter, 'toto');

        self::assertSame($backupManagerFilesystemAdapter, $sUT->destinationFilesystem());
        self::assertSame('toto', $sUT->destinationPath());
    }
}
