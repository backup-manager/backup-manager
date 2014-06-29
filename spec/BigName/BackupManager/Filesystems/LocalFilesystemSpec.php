<?php

namespace spec\BigName\BackupManager\Filesystems;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LocalFilesystemSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('BigName\BackupManager\Filesystems\LocalFilesystem');
    }

    function it_should_handle_types_with_case_insensitivity()
    {
        foreach (['local', 'LOCAL', 'LocaL'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    function it_should_deliver_the_correct_filesystem_type()
    {
        $this->get($this->getConfig())->getAdapter()->shouldHaveType('League\Flysystem\Adapter\Local');
    }

    function getConfig()
    {
        return [
            'root' => __DIR__,
        ];
    }
}
