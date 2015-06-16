<?php

namespace spec\BackupManager\Filesystems;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SftpFilesystemSpec extends ObjectBehavior {

    function it_is_initializable() {
        $this->shouldHaveType('BackupManager\Filesystems\SftpFilesystem');
    }

    function it_should_recognize_its_type_with_case_insensitivity() {
        foreach (['sftp', 'SFTP', 'SftP'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    function it_should_provide_an_instance_of_an_sftp_filesystem() {
        $this->get($this->getConfig())->getAdapter()
            ->shouldHaveType('League\Flysystem\Sftp\SftpAdapter');
    }

    function getConfig() {
        return [
            'host'       => 'sftp.example.com',
            'username'   => 'example.com',
            'password'   => 'password',
            'root'       => '/path/to/root',
            'port'       => 21,
            'timeout'    => 10,
            'privateKey' => '~/.ssh/private_key',
        ];
    }
}
