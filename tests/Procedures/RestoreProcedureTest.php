<?php

use BigName\DatabaseBackup\Procedures\RestoreProcedure;
use Mockery as m;

// This test could be expanded quite a bit more.
class RestoreProcedureTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $procedure = new RestoreProcedure(
            $this->getFilesystemProvider(),
            $this->getDatabaseProvider(),
            $this->getShellProcessor(),
            $this->getSequence()
        );
        $this->assertInstanceOf('BigName\DatabaseBackup\Procedures\RestoreProcedure', $procedure);
    }

    public function test_sequence_is_correct()
    {
        $filesystemProvider = $this->getFilesystemProvider();
        $filesystemProvider->shouldReceive('getType')->andReturn(m::mock('League\Flysystem\Filesystem'));

        $databaseProvider = $this->getDatabaseProvider();
        $databaseProvider->shouldReceive('getType')->andReturn(m::mock('BigName\DatabaseBackup\Databases\Database'));

        $sequence = $this->getSequence();
        $sequence->shouldReceive('add')->with(m::type('BigName\DatabaseBackup\Commands\Storage\TransferFile'))->once();
        $sequence->shouldReceive('add')->with(m::type('BigName\DatabaseBackup\Commands\Archiving\GunzipFile'))->once();
        $sequence->shouldReceive('add')->with(m::type('BigName\DatabaseBackup\Commands\Database\RestoreDatabase'))->once();
        $sequence->shouldReceive('add')->with(m::type('BigName\DatabaseBackup\Commands\Storage\DeleteFile'))->once();
        $sequence->shouldReceive('execute')->once();

        $procedure = new RestoreProcedure(
            $filesystemProvider,
            $databaseProvider,
            $this->getShellProcessor(),
            $sequence
        );

        $procedure->run('databaseName', 'destinationType', 'destinationPath');
    }

    private function getFilesystemProvider()
    {
        return m::mock('BigName\DatabaseBackup\Filesystems\FilesystemProvider');
    }

    private function getDatabaseProvider()
    {
        return m::mock('BigName\DatabaseBackup\Databases\DatabaseProvider');
    }

    private function getShellProcessor()
    {
        return m::mock('BigName\DatabaseBackup\ShellProcessing\ShellProcessor');
    }

    private function getSequence()
    {
        return m::mock('BigName\DatabaseBackup\Procedures\Sequence');
    }
}
