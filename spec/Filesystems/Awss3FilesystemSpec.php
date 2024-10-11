<?php

namespace spec\BackupManager\Filesystems;

use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
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
