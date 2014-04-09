<?php

use BigName\BackupManager\Filesystems\LocalFilesystem;
use Mockery as m;

class LocalFilesystemTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $local = new LocalFilesystem();
        $this->assertInstanceOf('BigName\BackupManager\Filesystems\LocalFilesystem', $local);
    }

    public function test_get_correct_filesystem()
    {
        // use __DIR__ as the working-path because Flysystem will create
        // anything fake for example.. 'foo' and you don't want that.
        $local = new LocalFilesystem();
        $filesystem = $local->get([
            'working-path' => __DIR__,
        ]);
        $this->assertInstanceOf('League\Flysystem\Adapter\Local', $filesystem->getAdapter());
    }
}
