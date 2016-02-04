<?php

namespace spec\BackupManager\Filesystems;

use BackupManager\Filesystems\Filesystem;
use BackupManager\Filesystems\FlysystemFilesystem;
use BackupManager\Filesystems\NoLocalFilesystemAvailable;
use League\Flysystem\MountManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FlysystemFilesystemSpec extends ObjectBehavior {

    function it_is_initializable(MountManager $files) {
        $this->beConstructedWith($files);

        $this->shouldHaveType(FlysystemFilesystem::class);
        $this->shouldImplement(Filesystem::class);
    }

    function it_can_write_to_a_stream(MountManager $files) {
        $files->writeStream('local://path/to/file.sql', 'from_file.sql')->shouldBeCalled();
        $files->getAdapter('local')->willReturn(true);
        $this->beConstructedWith($files);

        $this->writeStream('local', 'path/to/file.sql', 'from_file.sql');
    }

    function it_can_read_from_a_stream(MountManager $files) {
        $files->readStream('local://path/to/file.sql')->shouldBeCalled();
        $files->getAdapter('local')->willReturn(true);
        $this->beConstructedWith($files);

        $this->readStream('local', 'path/to/file.sql');
    }

    function it_can_delete(MountManager $files) {
        $files->delete('local://path/to/file.sql')->shouldBeCalled();
        $files->getAdapter('local')->willReturn(true);
        $this->beConstructedWith($files);

        $this->delete('local', 'path/to/file.sql');
    }

    function it_throws_when_no_local_filesystem_is_available(MountManager $files) {
        $this->beConstructedWith($files);

        $this->shouldThrow(NoLocalFilesystemAvailable::class)->duringInstantiation();
    }
}
