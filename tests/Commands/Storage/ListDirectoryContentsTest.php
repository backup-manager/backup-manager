<?php

use Mockery as m;

class ListDirectoryContentsTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $filesystem = m::mock('League\Flysystem\Filesystem');
        $list = new \BigName\BackupManager\Commands\Storage\ListDirectoryContents($filesystem, 'foo');
        $this->assertInstanceOf('BigName\BackupManager\Commands\Storage\ListDirectoryContents', $list);
    }

    public function test_file_listing_initiated()
    {
        $filesystem = m::mock('League\Flysystem\Filesystem');
        $filesystem->shouldReceive('listContents')->with('foo')->andReturn('baz')->once();

        $list = new \BigName\BackupManager\Commands\Storage\ListDirectoryContents($filesystem, 'foo');
        $contents = $list->execute();

        $this->assertEquals('baz', $contents);
    }
}
