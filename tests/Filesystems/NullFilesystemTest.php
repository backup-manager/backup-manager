<?php

use BigName\BackupManager\Filesystems\NullFilesystem;
use Mockery as m;

class NullFilesystemTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $this->assertInstanceOf('BigName\BackupManager\Filesystems\NullFilesystem', new NullFilesystem);
    }

    public function test_get_correct_filesystem()
    {
        $local = new NullFilesystem();
        $filesystem = $local->get([]);
        $this->assertInstanceOf('League\Flysystem\Adapter\NullAdapter', $filesystem->getAdapter());
    }
}
