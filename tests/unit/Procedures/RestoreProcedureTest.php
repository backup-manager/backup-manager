<?php

use BigName\BackupManager\Procedures\RestoreProcedure;
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
            $this->getCompressorProvider(),
            $this->getShellProcessor(),
            $this->getSequence(),
            ''
        );
        $this->assertInstanceOf('BigName\BackupManager\Procedures\RestoreProcedure', $procedure);
    }

    public function test_sequence_is_correct()
    {
        $filesystemProvider = $this->getFilesystemProvider();
        $filesystemProvider->shouldReceive('get')->andReturn(m::mock('League\Flysystem\Filesystem'));
        $filesystemProvider->shouldReceive('getConfig');

        $databaseProvider = $this->getDatabaseProvider();
        $databaseProvider->shouldReceive('get')->andReturn(m::mock('BigName\BackupManager\Databases\Database'));

        $compressorProvider = $this->getCompressorProvider();
        $compressorProvider->shouldIgnoreMissing();

        $sequence = $this->getSequence();
        $sequence->shouldReceive('add')->with(m::type('BigName\BackupManager\Tasks\Storage\TransferFile'))->once();
        $sequence->shouldReceive('add')->with(m::type('BigName\BackupManager\Tasks\Compression\DecompressFile'))->once();
        $sequence->shouldReceive('add')->with(m::type('BigName\BackupManager\Tasks\Database\RestoreDatabase'))->once();
        $sequence->shouldReceive('add')->with(m::type('BigName\BackupManager\Tasks\Storage\DeleteFile'))->once();
        $sequence->shouldReceive('execute')->once();

        $procedure = new RestoreProcedure(
            $filesystemProvider,
            $databaseProvider,
            $compressorProvider,
            $this->getShellProcessor(),
            $sequence,
            ''
        );

        $procedure->run('databaseName', 'destinationType', 'destinationPath');
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
        $provider = m::mock('BigName\BackupManager\Compressors\CompressorProvider');
        $provider->shouldReceive('get')->andReturn(new BigName\BackupManager\Compressors\GzipCompressor([]));
        return $provider;
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
