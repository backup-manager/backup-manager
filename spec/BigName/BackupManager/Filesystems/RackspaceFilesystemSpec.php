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

    function it_should_handle_types_with_case_insensitivity()
    {
        foreach (['rackspace', 'RackSpace', 'RACKSPACE'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    function it_should_deliver_the_correct_filesystem_type()
    {
        $this->shouldThrow('Guzzle\Http\Exception\ClientErrorResponseException')->during('get', [$this->getConfig()]);
        // can't really test this effectively because rackspace's sdk
        // tries to connect on instantiation...
    }

    function it_should_throw_an_exception_when_the_endpoint_is_incorrect()
    {
        $this->shouldThrow('Guzzle\Http\Exception\CurlException')->during('get', [[
            'username' => 'username',
            'key' => 'key',
            'root' => 'root',
            'zone' => 'zone',
            'endpoint' => 'incorrect_endpoint'
        ]]);
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
