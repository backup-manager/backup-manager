<?php

namespace spec\BackupManager\Filesystems;

use PhpSpec\ObjectBehavior;
use spec\BackupManager\Support\ExpectsAdapters;
use Prophecy\Argument;

class SftpFilesystemSpec extends ObjectBehavior
{
    use ExpectsAdapters;

    public function it_is_initializable()
    {
        $this->shouldHaveType('BackupManager\Filesystems\SftpFilesystem');
    }

    public function it_should_recognize_its_type_with_case_insensitivity()
    {
        foreach (['sftp', 'SFTP', 'SftP'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    public function it_should_provide_an_instance_of_an_sftp_filesystem()
    {
        $this->expectAdapter([
            'League\\Flysystem\\PhpseclibV3\\SftpAdapter',
        ], $this->getConfig());
    }

    public function it_should_accept_scalars_given_as_strings()
    {
        $this->expectAdapter([
            'League\\Flysystem\\PhpseclibV3\\SftpAdapter',
        ], ['port' => '22', 'timeout' => '10', 'useAgent' => '0'] + $this->getConfig());
    }

    public function getConfig()
    {
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
