<?php

use BigName\BackupManager\Filesystems\DropboxFilesystem;
use Mockery as m;

class DropboxFilesystemTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $dropbox = new DropboxFilesystem();
        $this->assertInstanceOf('BigName\BackupManager\Filesystems\DropboxFilesystem', $dropbox);
    }

    public function test_handles_correct_types()
    {
        $fs = new DropboxFilesystem();

        foreach (['dropbox', 'Dropbox', 'DropBOX'] as $type) {
            $this->assertTrue($fs->handles($type));
        }

        foreach ([null, 'foo'] as $type) {
            $this->assertFalse($fs->handles($type));
        }
    }

    public function test_get_correct_filesystem()
    {
        $dropbox = new DropboxFilesystem();
        $filesystem = $dropbox->get([
            'token' => 'token',
            'app' => 'app',
            'root' => 'some/directory/path',
        ]);
        $this->assertInstanceOf('League\Flysystem\Adapter\Dropbox', $filesystem->getAdapter());
    }
}
