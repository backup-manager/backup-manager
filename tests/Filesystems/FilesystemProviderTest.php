<?php

use BigName\BackupManager\Config\Config;
use BigName\BackupManager\Filesystems\FilesystemProvider;
use BigName\BackupManager\Filesystems\LocalFilesystem;
use Mockery as m;

class FilesystemProviderTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $provider = $this->getProvider();
        $this->assertInstanceOf('BigName\BackupManager\Filesystems\FilesystemProvider', $provider);
    }

    public function test_can_create_filesystem()
    {
        $provider = $this->getProvider();
        $filesystem = $provider->get('local');
        $this->assertInstanceOf('League\Flysystem\Filesystem', $filesystem);
    }

    public function test_unsupported_filesystem_exception()
    {
        $this->setExpectedException('BigName\BackupManager\Filesystems\FilesystemTypeNotSupported');
        $provider = $this->getProvider();
        $provider->get('unsupported');
    }

    public function test_can_get_available_providers()
    {
        $provider = $this->getProvider();
        $this->assertEquals(['local', 's3', 'unsupported', 'null'], $provider->getAvailableProviders());
    }

    public function test_can_get_config()
    {
        $config = m::mock('BigName\BackupManager\Config\Config');
        $config->shouldReceive('get')->with('foo', 'bar');

        $provider = new FilesystemProvider($config);
        $provider->getConfig('foo', 'bar');
    }

    /**
     * @return FilesystemProvider
     */
    private function getProvider()
    {
        $provider = new FilesystemProvider(Config::fromPhpFile('tests/config/storage.php'));
        $provider->add(new LocalFilesystem);
        return $provider;
    }
}
