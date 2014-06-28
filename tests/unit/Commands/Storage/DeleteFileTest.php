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
        $delete = new \BigName\BackupManager\Tasks\Storage\DeleteFile($filesystem, 'foo');
        $this->assertInstanceOf('BigName\BackupManager\Tasks\Storage\DeleteFile', $delete);
    }

    public function test_file_delete_initiated()
    {
        $filesystem = m::mock('League\Flysystem\Filesystem');
        $filesystem->shouldReceive('delete')->with('foo')->once();

        $delete = new \BigName\BackupManager\Tasks\Storage\DeleteFile($filesystem, 'foo');
        $delete->execute();
    }
}
