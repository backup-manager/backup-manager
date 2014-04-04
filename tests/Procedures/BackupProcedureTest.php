<?php

use BigName\DatabaseBackup\Procedures\BackupProcedure;
use Mockery as m;

// This test could be expanded quite a bit more.
class BackupProcedureTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $procedure = new BackupProcedure(
            $this->getFilesystemProvider(),
            $this->getDatabaseConfig(),
            $this->getShellProcessor(),
            $this->getSequence()
        );
        $this->assertInstanceOf('BigName\DatabaseBackup\Procedures\BackupProcedure', $procedure);
    }

    public function test_sequence_is_correct()
    {
        $filesystemProvider = $this->getFilesystemProvider();
        $filesystemProvider->shouldReceive('getType')->andReturn(m::mock('League\Flysystem\Filesystem'));

        $config = $this->getDatabaseConfig();
        $config->shouldIgnoreMissing();

        $sequence = $this->getSequence();
        $sequence->shouldReceive('add')->with(m::type('BigName\DatabaseBackup\Commands\Database\Mysql\DumpDatabase'))->once();
        $sequence->shouldReceive('add')->with(m::type('BigName\DatabaseBackup\Commands\Archiving\GzipFile'))->once();
        $sequence->shouldReceive('add')->with(m::type('BigName\DatabaseBackup\Commands\Storage\TransferFile'))->once();
        $sequence->shouldReceive('add')->with(m::type('BigName\DatabaseBackup\Commands\Storage\DeleteFile'))->once();
        $sequence->shouldReceive('execute')->once();

        $procedure = new BackupProcedure(
            $filesystemProvider,
            $config,
            $this->getShellProcessor(),
            $sequence
        );

        $procedure->run('databaseName', 'destinationType', 'destinationPath');
    }

    private function getFilesystemProvider()
    {
        return m::mock('BigName\DatabaseBackup\Filesystems\FilesystemProvider');
    }

    private function getDatabaseConfig()
    {
        return m::mock('BigName\DatabaseBackup\Config\Config');
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
