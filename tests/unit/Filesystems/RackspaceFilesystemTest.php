<?php

use BigName\BackupManager\Filesystems\RackspaceFilesystem;
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
        $this->assertInstanceOf('BigName\BackupManager\Filesystems\RackspaceFilesystem', $rackspace);
    }

    public function test_handles_correct_types()
    {
        $fs = new RackspaceFilesystem();

        foreach (['rackspace', 'RACKSPACE', 'Rackspace'] as $type) {
            $this->assertTrue($fs->handles($type));
        }

        foreach ([null, 'foo'] as $type) {
            $this->assertFalse($fs->handles($type));
        }
    }

    public function test_get_correct_filesystem()
    {
        $this->setExpectedException('Guzzle\Http\Exception\ClientErrorResponseException');

        $rackspace = new RackspaceFilesystem();
        $filesystem = $rackspace->get([
            'username' => 'username',
            'password' => 'password',
        ]);

        $this->assertInstanceOf('League\Flysystem\Adapter\Rackspace', $filesystem->getAdapter());
    }
}
