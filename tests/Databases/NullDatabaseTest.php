<?php

use BigName\BackupManager\Databases\NullDatabase;
use Mockery as m;

class NullDatabaseTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $mysql = new NullDatabase([]);
        $this->assertInstanceOf('BigName\BackupManager\Databases\NullDatabase', $mysql);
    }

    public function test_command_lines_are_empty()
    {
        $null = new NullDatabase([]);
        $this->assertEquals('', $null->getDumpCommandLine('input'));
        $this->assertEquals('', $null->getRestoreCommandLine('output'));
    }
}
