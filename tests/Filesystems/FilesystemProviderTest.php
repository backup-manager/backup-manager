<?php

use BigName\BackupManager\Config\Config;
use BigName\BackupManager\Filesystems\FilesystemProvider;
use Mockery as m;

class FilesystemProviderTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $provider = new FilesystemProvider(new Config('tests/config/storage.php'));
        $this->assertInstanceOf('BigName\BackupManager\Filesystems\FilesystemProvider', $provider);
    }

    public function test_can_create_filesystem()
    {
        $provider = new FilesystemProvider(new Config('tests/config/storage.php'));
        $filesystem = $provider->get('local');
        $this->assertInstanceOf('League\Flysystem\Filesystem', $filesystem);
    }

    public function test_unsupported_filesystem_exception()
    {
        $this->setExpectedException('BigName\BackupManager\Filesystems\FilesystemTypeNotSupported');
        $provider = new FilesystemProvider(new Config('tests/config/storage.php'));
        $provider->get('unsupported');
    }

    public function test_receive_null_object()
    {
        $provider = new FilesystemProvider(new Config('tests/config/storage.php'));
        $null = $provider->get('null');
        $this->assertInstanceOf('League\Flysystem\Filesystem', $null);
        $this->assertInstanceOf('League\Flysystem\Adapter\NullAdapter', $null->getAdapter());
    }
}
