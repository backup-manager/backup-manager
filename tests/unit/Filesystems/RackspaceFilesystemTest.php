<?php

use BigName\BackupManager\Filesystems\RackspaceFilesystem;
use Mockery as m;
use OpenCloud\Rackspace;

class RackspaceFilesystemTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $this->assertInstanceOf('BigName\BackupManager\Filesystems\RackspaceFilesystem', new RackspaceFilesystem);
    }

    public function test_handles_correct_types()
    {
        $fs = new RackspaceFilesystem;

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

        $rackspace = new RackspaceFilesystem;
        $filesystem = $rackspace->get([
            'username' => 'username',
            'key' => 'key',
            'root' => 'root',
            'zone' => 'zone',
            'endpoint' => Rackspace::UK_IDENTITY_ENDPOINT
        ]);

        $this->assertInstanceOf('League\Flysystem\Adapter\Rackspace', $filesystem->getAdapter());
    }

    public function test_throws_when_endpoint_is_incorrect()
    {
        $this->setExpectedException('Guzzle\Http\Exception\CurlException');
        $rackspace = new RackspaceFilesystem;
        $rackspace->get([
            'username' => 'username',
            'key' => 'key',
            'root' => 'root',
            'zone' => 'zone',
            'endpoint' => 'incorrect_endpoint'
        ]);
    }
}
