<?php

use BigName\DatabaseBackup\Config;
use Mockery as m;

class FilesystemProviderTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $config = $_SERVER['TRAVIS_BUILD_DIR'] . '/tests/config/storage.php';
        $provider = new \BigName\DatabaseBackup\Filesystems\FilesystemProvider(new Config($config));
        $this->assertInstanceOf('BigName\DatabaseBackup\Filesystems\FilesystemProvider', $provider);
    }
}
