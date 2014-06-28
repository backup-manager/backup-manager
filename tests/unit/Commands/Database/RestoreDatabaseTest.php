<?php

use BigName\BackupManager\Tasks\Database\RestoreDatabase;
use Mockery as m;

class RestoreDatabaseTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $database = m::mock('BigName\BackupManager\Databases\Database');
        $shell = m::mock('BigName\BackupManager\ShellProcessing\ShellProcessor');
        $dumpDatabase = new RestoreDatabase($database, 'foo', $shell);
        $this->assertInstanceOf('BigName\BackupManager\Tasks\Database\RestoreDatabase', $dumpDatabase);
    }

    public function test_command_is_processed()
    {
        $database = m::mock('BigName\BackupManager\Databases\Database');
        $database->shouldReceive('getRestoreCommandLine')->andReturn('foo');

        $shell = m::mock('BigName\BackupManager\ShellProcessing\ShellProcessor');
        $shell->shouldReceive('process')->with('foo')->once();
        
        $dumpDatabase = new RestoreDatabase($database, 'foo', $shell);
        $dumpDatabase->execute();
    }
}
