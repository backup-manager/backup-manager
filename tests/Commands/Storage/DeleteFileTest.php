<?php

use Mockery as m;

class DeleteFileTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $filesystem = m::mock('League\Flysystem\Filesystem');
        $delete = new \BigName\DatabaseBackup\Commands\Storage\DeleteFile($filesystem, 'foo');
        $this->assertInstanceOf('BigName\DatabaseBackup\Commands\Storage\DeleteFile', $delete);
    }

    public function test_file_delete_initiated()
    {
        $filesystem = m::mock('League\Flysystem\Filesystem');
        $filesystem->shouldReceive('delete')->with('foo')->once();

        $delete = new \BigName\DatabaseBackup\Commands\Storage\DeleteFile($filesystem, 'foo');
        $delete->execute();
    }
}
