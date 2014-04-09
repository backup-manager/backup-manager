<?php

use BigName\DatabaseBackup\Config\Config;
use BigName\DatabaseBackup\Filesystems\FilesystemProvider;
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
        $this->assertInstanceOf('BigName\DatabaseBackup\Filesystems\FilesystemProvider', $provider);
    }

    public function test_can_create_filesystem()
    {
        $provider = new FilesystemProvider(new Config('tests/config/storage.php'));
        $filesystem = $provider->get('local');
        $this->assertInstanceOf('League\Flysystem\Filesystem', $filesystem);
    }

    public function test_unsupported_filesystem_exception()
    {
        $this->setExpectedException('BigName\DatabaseBackup\Filesystems\FilesystemTypeNotSupported');
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
