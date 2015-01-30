<?php

namespace spec\BigName\BackupManager\Procedures;

use BigName\BackupManager\Compressors\CompressorProvider;
use BigName\BackupManager\Databases\DatabaseProvider;
use BigName\BackupManager\Filesystems\FilesystemProvider;
use BigName\BackupManager\Procedures\Sequence;
use BigName\BackupManager\ShellProcessing\ShellProcessor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BackupProcedureSpec extends ObjectBehavior
{
    function it_is_initializable(FilesystemProvider $filesystemProvider, DatabaseProvider $databaseProvider, CompressorProvider $compressorProvider, ShellProcessor $shellProcessor, Sequence $sequence)
    {
        $this->beConstructedWith($filesystemProvider, $databaseProvider, $compressorProvider, $shellProcessor, $sequence);
        $this->shouldHaveType('BigName\BackupManager\Procedures\BackupProcedure');
    }
}
