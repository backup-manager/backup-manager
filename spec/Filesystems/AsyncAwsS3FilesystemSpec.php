<?php

namespace spec\BackupManager\Filesystems;

use PhpSpec\ObjectBehavior;
use spec\BackupManager\Support\ExpectsAdapters;

class AsyncAwsS3FilesystemSpec extends ObjectBehavior
{
    use ExpectsAdapters;

    public function it_is_initializable()
    {
        $this->shouldHaveType('BackupManager\Filesystems\AsyncAwsS3Filesystem');
    }

    public function it_should_recognize_its_type_with_case_insensitivity()
    {
        foreach (['asyncawss3', 'AsyncAWSS3', 'AsyncAwsS3', 'async-awss3', 'async-aws-s3'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo', 'awss3'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    public function it_should_provide_an_instance_of_an_async_aws_s3_filesystem()
    {
        $this->expectAdapter([
            'League\\Flysystem\\AsyncAwsS3\\AsyncAwsS3Adapter',
        ], $this->getConfig());
    }

    public function getConfig()
    {
        return [
            'key'    => 'key',
            'secret' => 'secret',
            'region' => 'us-east-1',
            'bucket' => 'bucket',
            'root'   => 'prefix',
        ];
    }
}
