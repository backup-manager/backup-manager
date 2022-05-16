<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Tests;

use Fezfez\BackupManager\Procedures\Backup;
use Fezfez\BackupManager\Procedures\Restore;
use PHPUnit\Framework\TestCase;

class ManagerSpec extends TestCase
{
    public function testCreateABackupProcedure(): void
    {
        $this->makeBackup()->shouldHaveType(Backup::class);
    }

    public function testCreateARestoreProcedure(): void
    {
        $this->makeRestore()->shouldHaveType(Restore::class);
    }
}
