<?php

use Mockery as m;

class TransferFileTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $filesystem = m::mock('League\Flysystem\Filesystem');
        $transfer = new \BigName\DatabaseBackup\Commands\Storage\TransferFile($filesystem, 'foo', $filesystem, 'bar');
        $this->assertInstanceOf('BigName\DatabaseBackup\Commands\Storage\TransferFile', $transfer);
    }

    public function test_file_transfer_initiated()
    {
        $source = m::mock('League\Flysystem\Filesystem');
        $source->shouldReceive('readStream')->with('foo')->andReturn('baz')->once();

        $destination = m::mock('League\Flysystem\Filesystem');
        $destination->shouldReceive('writeStream')->with('bar', 'baz')->once();

        $delete = new \BigName\DatabaseBackup\Commands\Storage\TransferFile($source, 'foo', $destination, 'bar');
        $delete->execute();
    }
}
