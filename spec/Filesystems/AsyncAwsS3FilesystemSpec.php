<?php

namespace spec\BackupManager\Filesystems;

use PhpSpec\ObjectBehavior;

class AsyncAwsS3FilesystemSpec extends ObjectBehavior {

    function it_is_initializable() {
        $this->shouldHaveType('BackupManager\Filesystems\AsyncAwsS3Filesystem');
    }

    function it_should_recognize_its_type_with_case_insensitivity() {
        foreach (['asyncawss3', 'AsyncAWSS3', 'AsyncAwsS3'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    function it_should_provide_an_instance_of_an_s3_filesystem() {
        $this->get($this->getConfig())->getAdapter()->shouldHaveType('AsyncAws\Flysystem\S3\S3FilesystemV1');
    }

    function getConfig() {
        return [
            'key'    => 'key',
            'secret' => 'secret',
            'region' => 'us-east-1',
            'bucket' => 'bucket',
            'root'   => 'prefix',
        ];
    }
}
