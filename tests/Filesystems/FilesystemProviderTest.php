<?php

use BigName\DatabaseBackup\Config\Config;
use Mockery as m;

class FilesystemProviderTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $provider = new \BigName\DatabaseBackup\Filesystems\FilesystemProvider(new Config('tests/config/storage.php'));
        $this->assertInstanceOf('BigName\DatabaseBackup\Filesystems\FilesystemProvider', $provider);
    }

    public function test_can_create_filesystem()
    {
        $provider = new \BigName\DatabaseBackup\Filesystems\FilesystemProvider(new Config('tests/config/storage.php'));
        $filesystem = $provider->getType('local');
        $this->assertInstanceOf('League\Flysystem\Filesystem', $filesystem);
    }

    public function test_unsupported_filesystem_exception()
    {
        $this->setExpectedException('BigName\DatabaseBackup\Filesystems\FilesystemTypeNotSupported');

        $provider = new \BigName\DatabaseBackup\Filesystems\FilesystemProvider(new Config('tests/config/storage.php'));
        $provider->getType('unsupported');
    }
}
