<?php

namespace spec\BackupManager\Procedures;

use BackupManager\Compressors\CompressorProvider;
use BackupManager\Databases\DatabaseProvider;
use BackupManager\Filesystems\FilesystemProvider;
use BackupManager\Procedures\Sequence;
use BackupManager\ShellProcessing\ShellProcessor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RestoreProcedureSpec extends ObjectBehavior {

    function it_is_initializable(FilesystemProvider $filesystemProvider, DatabaseProvider $databaseProvider, CompressorProvider $compressorProvider, ShellProcessor $shellProcessor, Sequence $sequence) {
        $this->beConstructedWith($filesystemProvider, $databaseProvider, $compressorProvider, $shellProcessor, $sequence);
        $this->shouldHaveType('BackupManager\Procedures\RestoreProcedure');
    }
}
