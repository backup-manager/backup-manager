<?php

namespace spec\BigName\BackupManager\Filesystems;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RackspaceFilesystemSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('BigName\BackupManager\Filesystems\RackspaceFilesystem');
    }

    function it_should_recognize_its_type_with_case_insensitivity()
    {
        foreach (['rackspace', 'RackSpace', 'RACKSPACE'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    function it_should_be_basically_untestable_because_the_library_is_bad()
    {
        // can't really test this effectively because rackspace's sdk
        // tries to connect on instantiation...
        $this->shouldThrow('Guzzle\Http\Exception\ClientErrorResponseException')->during('get', [$this->getConfig()]);
    }

    function getConfig()
    {
        return [
            'username' => 'username',
            'key' => 'key',
            'root' => 'root',
            'zone' => 'zone',
            'endpoint' => 0
        ];
    }
}
