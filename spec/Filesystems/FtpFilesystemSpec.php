<?php

namespace spec\BackupManager\Filesystems;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FtpFilesystemSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('BackupManager\Filesystems\FtpFilesystem');
    }

    public function it_should_recognize_its_type_with_case_insensitivity()
    {
        foreach (['ftp', 'Ftp', 'FTP'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    public function it_should_provide_an_instance_of_an_ftp_filesystem()
    {
        @$this->get($this->getConfig())->getAdapter()
            ->shouldHaveType('League\Flysystem\Adapter\Ftp');
    }

    public function getConfig()
    {
        return [
            'host'     => 'ftp.example.com',
            'username' => 'example.com',
            'password' => 'password',
        ];
    }
}
