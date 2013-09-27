<?php

use McCool\DatabaseBackup\BackupProcedure;
use Mockery as m;

class BackupProcedureTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testPlaindump()
    {
        $dumper = m::mock('McCool\DatabaseBackup\Dumpers\DumperInterface');
        $dumper->shouldReceive('dump', 'getOutputFilename');

        $backup = new BackupProcedure($dumper);
        $backup->backup();
    }

    public function testArchivedDump()
    {
        $dumper = m::mock('McCool\DatabaseBackup\Dumpers\DumperInterface');
        $dumper->shouldReceive('dump', 'getOutputFilename');

        $archiver = m::mock('McCool\DatabaseBackup\Archivers\ArchiverInterface');
        $archiver->shouldReceive('setInputFilename', 'archive', 'getOutputFilename');

        $backup = new BackupProcedure($dumper);
        $backup->setArchiver($archiver);

        $backup->backup();
    }

    public function testStoredDump()
    {
        $dumper = m::mock('McCool\DatabaseBackup\Dumpers\DumperInterface');
        $dumper->shouldReceive('dump', 'getOutputFilename');

        $storer = m::mock('McCool\DatabaseBackup\Storers\StorerInterface');
        $storer->shouldReceive('setInputFilename', 'store');

        $backup = new BackupProcedure($dumper);
        $backup->setStorer($storer);

        $backup->backup();
    }

    public function testArchivedStoredDump()
    {
        $dumper = m::mock('McCool\DatabaseBackup\Dumpers\DumperInterface');
        $dumper->shouldReceive('dump', 'getOutputFilename');

        $archiver = m::mock('McCool\DatabaseBackup\Archivers\ArchiverInterface');
        $archiver->shouldReceive('setInputFilename', 'archive', 'getOutputFilename');

        $storer = m::mock('McCool\DatabaseBackup\Storers\StorerInterface');
        $storer->shouldReceive('setInputFilename', 'store');

        $backup = new BackupProcedure($dumper);
        $backup->setArchiver($archiver);
        $backup->setStorer($storer);

        $backup->backup();
    }
}