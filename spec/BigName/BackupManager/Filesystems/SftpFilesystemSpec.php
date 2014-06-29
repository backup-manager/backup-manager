<?php

namespace spec\BigName\BackupManager\Filesystems;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SftpFilesystemSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('BigName\BackupManager\Filesystems\SftpFilesystem');
    }

    function it_should_handle_types_with_case_insensitivity()
    {
        foreach (['sftp', 'SFTP', 'SftP'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    function it_should_deliver_the_correct_filesystem_type()
    {
        $this->get($this->getConfig())->getAdapter()->shouldHaveType('League\Flysystem\Adapter\Sftp');
    }

    function getConfig()
    {
        return [
            'host' => 'sftp.example.com',
            'username' => 'example.com',
            'password' => 'password',
            'root' => '/path/to/root',
            'port' => 21,
            'timeout' => 10,
            'privateKey' => '~/.ssh/private_key',
        ];
    }
}
