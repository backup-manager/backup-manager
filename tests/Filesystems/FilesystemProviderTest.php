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
        dd($_SERVER);
        $provider = new \BigName\DatabaseBackup\Filesystems\FilesystemProvider(new Config(dirname(__FILE__) . '/../config/storage.php'));
        $this->assertInstanceOf('BigName\DatabaseBackup\Filesystems\FilesystemProvider', $provider);
    }
}
