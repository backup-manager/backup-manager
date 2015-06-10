<?php

namespace spec\BackupManager\Tasks\Storage;

use League\Flysystem\Filesystem;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DeleteFileSpec extends ObjectBehavior {

    function it_is_initializable(Filesystem $filesystem) {
        $this->beConstructedWith($filesystem, 'path');
        $this->shouldHaveType('BackupManager\Tasks\Storage\DeleteFile');
    }

    function it_should_execute_the_delete_file_command(Filesystem $filesystem) {
        $filesystem->delete('path')->shouldBeCalled();

        $this->beConstructedWith($filesystem, 'path');
        $this->execute();
    }
}
