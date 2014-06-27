<?php

use BigName\BackupManager\Procedures\BackupProcedure;
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
            $this->getDatabaseProvider(),
            $this->getCompressorProvider(),
            $this->getShellProcessor(),
            $this->getSequence(),
            ''
        );
        $this->assertInstanceOf('BigName\BackupManager\Procedures\BackupProcedure', $procedure);
    }

    public function test_sequence_is_correct()
    {
        $filesystemProvider = $this->getFilesystemProvider();
        $filesystemProvider->shouldReceive('get')->andReturn(m::mock('League\Flysystem\Filesystem'));
        $filesystemProvider->shouldReceive('getConfig')->andReturn('local');

        $databaseProvider = $this->getDatabaseProvider();
        $databaseProvider->shouldReceive('get')->andReturn(m::mock('BigName\BackupManager\Databases\Database'));

        $compressorProvider = $this->getCompressorProvider();
        $compressor = m::mock('BigName\BackupManager\Compressors\Compressor');
        $compressor->shouldIgnoreMissing();
        $compressorProvider->shouldReceive('get')->andReturn($compressor);

        $sequence = $this->getSequence();

        $sequence->shouldReceive('add')->with(m::type('BigName\BackupManager\Tasks\Database\DumpDatabase'))->once();
        $sequence->shouldReceive('add')->with(m::type('BigName\BackupManager\Tasks\Compression\CompressFile'))->once();
        $sequence->shouldReceive('add')->with(m::type('BigName\BackupManager\Tasks\Storage\TransferFile'))->once();
        $sequence->shouldReceive('add')->with(m::type('BigName\BackupManager\Tasks\Storage\DeleteFile'))->once();
        $sequence->shouldReceive('execute')->once();


        $procedure = new BackupProcedure(
            $filesystemProvider,
            $databaseProvider,
            $compressorProvider,
            $this->getShellProcessor(),
            $sequence,
            ''
        );

        $procedure->run('databaseName', 'destinationType', 'destinationPath', 'gzip');
    }

    private function getFilesystemProvider()
    {
        return m::mock('BigName\BackupManager\Filesystems\FilesystemProvider');
    }

    private function getDatabaseProvider()
    {
        return m::mock('BigName\BackupManager\Databases\DatabaseProvider');
    }

    private function getCompressorProvider()
    {
        return m::mock('BigName\BackupManager\Compressors\CompressorProvider');
    }

    private function getShellProcessor()
    {
        return m::mock('BigName\BackupManager\ShellProcessing\ShellProcessor');
    }

    private function getSequence()
    {
        return m::mock('BigName\BackupManager\Procedures\Sequence');
    }
}
