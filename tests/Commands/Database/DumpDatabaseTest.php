<?php

use BigName\DatabaseBackup\Commands\Database\DumpDatabase;
use Mockery as m;

class DumpDatabaseTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $database = m::mock('BigName\DatabaseBackup\Databases\Database');
        $shell = m::mock('BigName\DatabaseBackup\ShellProcessing\ShellProcessor');
        $dumpDatabase = new DumpDatabase($database, 'foo', $shell);
        $this->assertInstanceOf('BigName\DatabaseBackup\Commands\Database\DumpDatabase', $dumpDatabase);
    }

    public function test_command_is_processed()
    {
        $database = m::mock('BigName\DatabaseBackup\Databases\Database');
        $database->shouldReceive('getDumpCommandLine')->andReturn('foo');

        $shell = m::mock('BigName\DatabaseBackup\ShellProcessing\ShellProcessor');
        $shell->shouldReceive('process')->with('foo')->once();

        $dumpDatabase = new DumpDatabase($database, 'foo', $shell);
        $dumpDatabase->execute();
    }
}
