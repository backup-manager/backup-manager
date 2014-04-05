<?php

use BigName\DatabaseBackup\Filesystems\RackspaceFilesystem;
use Mockery as m;

class RackspaceFilesystemTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $rackspace = new RackspaceFilesystem();
        $this->assertInstanceOf('BigName\DatabaseBackup\Filesystems\RackspaceFilesystem', $rackspace);
    }

    public function test_get_correct_filesystem()
    {
        $rackspace = new RackspaceFilesystem();
        $filesystem = $rackspace->get([
            'username' => 'username',
            'password' => 'password',
        ]);
        $this->assertInstanceOf('League\Flysystem\Adapter\Rackspace', $filesystem->getAdapter());
    }
}
