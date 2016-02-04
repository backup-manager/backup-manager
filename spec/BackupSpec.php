<?php

namespace spec\BackupManager;

use BackupManager\Backup;
use BackupManager\Compressors\Compressor;
use BackupManager\Databases\Database;
use BackupManager\Filesystems\Filesystem;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BackupSpec extends ObjectBehavior {

    function it_is_initializable(Database $database, Filesystem $filesystem, Compressor $compressor) {
        $this->beConstructedWith($database, $filesystem, $compressor);
        $this->shouldHaveType(Backup::class);
    }
}
