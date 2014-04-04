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
        $provider = new \BigName\DatabaseBackup\Filesystems\FilesystemProvider(new Config(__DIR__ . '/../config/storage.php'));
        $this->assertInstanceOf('BigName\DatabaseBackup\Filesystems\FilesystemProvider', $provider);
    }
}
