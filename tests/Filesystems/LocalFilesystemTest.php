<?php

use BigName\DatabaseBackup\Filesystems\LocalFilesystem;
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
        $this->assertInstanceOf('BigName\DatabaseBackup\Filesystems\LocalFilesystem', $local);
    }

    public function test_get_correct_filesystem()
    {
        $local = new LocalFilesystem();
        $filesystem = $local->get([
            'working-path' => 'foo',
        ]);
        $this->assertInstanceOf('League\Flysystem\Adapter\Local', $filesystem->getAdapter());
    }
}
