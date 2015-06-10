<?php

namespace spec\BackupManager\Filesystems;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LocalFilesystemSpec extends ObjectBehavior {

    function it_is_initializable() {
        $this->shouldHaveType('BackupManager\Filesystems\LocalFilesystem');
    }

    function it_should_recognize_its_type_with_case_insensitivity() {
        foreach (['local', 'LOCAL', 'LocaL'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    function it_should_provide_an_instance_of_a_local_filesystem() {
        $this->get($this->getConfig())->getAdapter()->shouldHaveType('League\Flysystem\Adapter\Local');
    }

    function getConfig() {
        return [
            'root' => __DIR__,
        ];
    }
}
