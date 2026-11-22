<?php

namespace spec\BackupManager\Tasks\Storage;

use League\Flysystem\FilesystemOperator;
use PhpSpec\ObjectBehavior;

class DeleteFileSpec extends ObjectBehavior
{
    public function it_is_initializable(FilesystemOperator $filesystem)
    {
        $this->beConstructedWith($filesystem, 'path');
        $this->shouldHaveType('BackupManager\Tasks\Storage\DeleteFile');
    }

    public function it_should_execute_the_delete_file_command(FilesystemOperator $filesystem)
    {
        $filesystem->delete('path')->shouldBeCalled();

        $this->beConstructedWith($filesystem, 'path');
        $this->execute();
    }
}
