<?php

namespace spec\BackupManager\Tasks\Storage;

use PhpSpec\ObjectBehavior;
use spec\BackupManager\Support\StorageDouble;

class DeleteFileSpec extends ObjectBehavior
{
    public function it_is_initializable(StorageDouble $filesystem)
    {
        $this->beConstructedWith($filesystem, 'path');
        $this->shouldHaveType('BackupManager\Tasks\Storage\DeleteFile');
    }

    public function it_should_execute_the_delete_file_command(StorageDouble $filesystem)
    {
        $filesystem->delete('path')->shouldBeCalled()->willReturn(null);

        $this->beConstructedWith($filesystem, 'path');
        $this->execute()->shouldBe(true);
    }

    public function it_should_report_a_flysystem_v1_delete_failure(StorageDouble $filesystem)
    {
        $filesystem->delete('path')->willReturn(false);

        $this->beConstructedWith($filesystem, 'path');
        $this->execute()->shouldBe(false);
    }
}
