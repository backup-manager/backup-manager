<?php

namespace spec\BackupManager\Filesystems;

use BackupManager\Filesystems\FlysystemFilesystem;
use BackupManager\Filesystems\LocalFilesystem;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\MountManager;
use PhpSpec\ObjectBehavior;

class FlysystemFilesystemSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(FlysystemFilesystem::class);
    }

    public function it_should_recognize_its_type_with_case_insensitivity()
    {
        foreach (['flysystem', 'Flysystem', 'FlySystem'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    public function it_should_provide_a_filesystem_by_name(FilesystemOperator $filesystem)
    {
        $this->beConstructedWith(['s3_backup' => $filesystem]);
        $this->get(['name' => 's3_backup'])->shouldBe($filesystem);
    }

    public function it_should_provide_a_mount_manager_by_name()
    {
        $manager = new MountManager(['upload' => (new LocalFilesystem())->get(['root' => sys_get_temp_dir()])]);
        $this->beConstructedWith(['mounts' => $manager]);
        $this->get(['name' => 'mounts'])->shouldBe($manager);
    }

    public function it_should_reject_an_unknown_name(FilesystemOperator $filesystem)
    {
        $this->beConstructedWith(['s3_backup' => $filesystem]);
        $this->shouldThrow(\InvalidArgumentException::class)->during('get', [['name' => 'other']]);
    }
}
