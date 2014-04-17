<?php

use BigName\BackupManager\Filesystems\Awss3Filesystem;
use Mockery as m;

class AwsS3FilesystemTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $s3 = new Awss3Filesystem();
        $this->assertInstanceOf('BigName\BackupManager\Filesystems\AwsS3Filesystem', $s3);
    }

    public function test_handles_correct_types()
    {
        $fs = new Awss3Filesystem();

        foreach (['awss3', 'AWSS3', 'AwsS3'] as $type) {
            $this->assertTrue($fs->handles($type));
        }

        foreach ([null, 'foo'] as $type) {
            $this->assertFalse($fs->handles($type));
        }
    }

    public function test_get_correct_filesystem()
    {
        $s3 = new Awss3Filesystem();
        $filesystem = $s3->get([
            'key' => 'key',
            'secret' => 'secret',
            'region' => Aws\Common\Enum\Region::US_EAST_1,
            'bucket' => 'bucket',
        ]);
        $this->assertInstanceOf('League\Flysystem\Adapter\AwsS3', $filesystem->getAdapter());
    }
}
