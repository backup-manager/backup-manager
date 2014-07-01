<?php

namespace spec\BigName\BackupManager;

use BigName\BackupManager\Compressors\CompressorProvider;
use BigName\BackupManager\Databases\DatabaseProvider;
use BigName\BackupManager\Filesystems\FilesystemProvider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    function let(FilesystemProvider $filesystems, DatabaseProvider $databases, CompressorProvider $compressors)
    {
        $this->beConstructedWith($filesystems, $databases, $compressors);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('BigName\BackupManager\Manager');
    }

    function it_should_create_a_backup_procedure()
    {
        $this->makeBackup()->shouldHaveType('BigName\BackupManager\Procedures\BackupProcedure');
    }

    function it_should_create_a_restore_procedure()
    {
        $this->makeRestore()->shouldHaveType('BigName\BackupManager\Procedures\RestoreProcedure');
    }
}
