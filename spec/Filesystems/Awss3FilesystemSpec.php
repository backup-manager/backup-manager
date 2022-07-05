<?php

namespace spec\BackupManager\Filesystems;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class Awss3FilesystemSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('BackupManager\Filesystems\Awss3Filesystem');
    }

    public function it_should_recognize_its_type_with_case_insensitivity()
    {
        foreach (['awss3', 'AWSS3', 'AwsS3'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    public function it_should_provide_an_instance_of_an_s3_filesystem()
    {
        $this->get($this->getConfig())->getAdapter()->shouldHaveType('League\Flysystem\AwsS3v3\AwsS3Adapter');
    }

    public function getConfig()
    {
        return [
            'key'    => 'key',
            'secret' => 'secret',
            'region' => 'us-east-1',
            'bucket' => 'bucket',
            'root'   => 'prefix',
            'version' => 'latest'
        ];
    }
}
