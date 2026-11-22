<?php

namespace spec\BackupManager\Filesystems;

use PhpSpec\ObjectBehavior;
use spec\BackupManager\Support\ExpectsAdapters;
use Prophecy\Argument;

class FtpFilesystemSpec extends ObjectBehavior
{
    use ExpectsAdapters;

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
        $this->expectAdapter([
            'League\\Flysystem\\Ftp\\FtpAdapter',
        ], $this->getConfig());
    }

    public function it_should_accept_scalars_given_as_strings()
    {
        $this->expectAdapter([
            'League\\Flysystem\\Ftp\\FtpAdapter',
        ], ['port' => '21', 'timeout' => '30', 'ssl' => '1', 'passive' => 'true'] + $this->getConfig());
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
