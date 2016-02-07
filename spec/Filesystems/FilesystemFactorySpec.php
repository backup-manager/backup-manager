<?php

namespace spec\BackupManager\Filesystems;

use BackupManager\Config\Config;
use BackupManager\Filesystems\FilesystemFactory;
use BackupManager\Filesystems\FlysystemFilesystem;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FilesystemFactorySpec extends ObjectBehavior {

    function it_is_initializable(Config $config) {
        $this->beConstructedWith($config);
        $this->shouldHaveType(FilesystemFactory::class);
    }

    function it_can_make_flysystem_filesystem(Config $config) {
        $config->get(Argument::any())->willReturn([
            'local' => [
                'driver' => 'local',
                'root' => '/'
            ]
        ]);
        $this->beConstructedWith($config);
        $this->make()->shouldBeAnInstanceOf(FlysystemFilesystem::class);
    }
}
