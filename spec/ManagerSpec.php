<?php

namespace spec\BackupManager;

use BackupManager\Compressors\CompressorProvider;
use BackupManager\Databases\DatabaseProvider;
use BackupManager\Filesystems\FilesystemProvider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    public function let(FilesystemProvider $filesystems, DatabaseProvider $databases, CompressorProvider $compressors)
    {
        $this->beConstructedWith($filesystems, $databases, $compressors);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('BackupManager\Manager');
    }

    public function it_should_create_a_backup_procedure()
    {
        $this->makeBackup()->shouldHaveType('BackupManager\Procedures\BackupProcedure');
    }

    public function it_should_create_a_restore_procedure()
    {
        $this->makeRestore()->shouldHaveType('BackupManager\Procedures\RestoreProcedure');
    }
}
