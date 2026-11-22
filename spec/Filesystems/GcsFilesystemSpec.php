<?php

namespace spec\BackupManager\Filesystems;

use PhpSpec\ObjectBehavior;
use spec\BackupManager\Support\ExpectsAdapters;
use Prophecy\Argument;

class GcsFilesystemSpec extends ObjectBehavior
{
    use ExpectsAdapters;

    public function it_is_initializable()
    {
        $this->shouldHaveType('BackupManager\Filesystems\GcsFilesystem');
    }

    public function it_should_recognize_its_type_with_case_insensitivity()
    {
        foreach (['gcs', 'GCS', 'Gcs'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    public function it_should_provide_an_instance_of_an_gcp_filesystem()
    {
        $this->expectAdapter([
            'League\\Flysystem\\GoogleCloudStorage\\GoogleCloudStorageAdapter',
        ], $this->getConfig());
    }

    public function getConfig()
    {
        return [
            'type'         => 'gcs',
            'keyFilePath'  => '',
            'project'      => 'example',
            'bucket'       => 'example',
            'prefix'       => '',
        ];
    }
}
