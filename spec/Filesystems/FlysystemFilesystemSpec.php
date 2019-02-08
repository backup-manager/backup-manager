<?php

namespace spec\BackupManager\Filesystems;

use BackupManager\Filesystems\FlysystemFilesystem;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\MountManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FlysystemFilesystemSpec extends ObjectBehavior {

    function it_is_initializable() {
        $this->shouldHaveType(FlysystemFilesystem::class);
    }

    function it_should_recognize_its_type_with_case_insensitivity() {
        foreach (['flysystem', 'Flysystem', 'FlySystem'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    function it_should_provide_an_instance_of_an_flysystem_filesystem(FilesystemInterface $filesystem) {
        $name = 's3_backup';
        $this->beConstructedWith([$name => $filesystem]);
        $this->get(['name' => $name])->shouldHaveType(FilesystemInterface::class);
    }

    function it_should_provide_an_instance_of_an_flysystem_filesystem_in_mount_manager(MountManager $manager, FilesystemInterface $filesystem) {
        $prefix = 'upload';
        $manager->getFilesystem(Argument::exact($prefix))->willReturn($filesystem);
        $this->beConstructedWith([], $manager);
        $this->get(['prefix' => $prefix])->shouldHaveType(FilesystemInterface::class);
    }
}
