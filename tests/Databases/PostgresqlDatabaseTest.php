<?php

use BigName\DatabaseBackup\Databases\PostgresqlDatabase;
use Mockery as m;

class PostgresqlDatabaseTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $mysql = new PostgresqlDatabase([]);
        $this->assertInstanceOf('BigName\DatabaseBackup\Databases\PostgresqlDatabase', $mysql);
    }

    public function test_get_dump_command()
    {
        $postgres = new PostgresqlDatabase([
            'host' => 'host',
            'port' => '3306',
            'user' => 'user',
            'pass' => 'password',
            'database' => 'database',
        ]);
        $this->assertEquals("pg_dump --host='host' --port='3306' --username='user' 'database' -f 'outputPath'", $postgres->getDumpCommandLine('outputPath'));
    }

    public function test_get_restore_command()
    {
        $postgres = new PostgresqlDatabase([
            'host' => 'host',
            'port' => '3306',
            'user' => 'user',
            'pass' => 'password',
            'database' => 'database',
        ]);
        $this->assertEquals("psql --host='host' --port='3306' --user='user' 'database' -f 'outputPath'", $postgres->getRestoreCommandLine('outputPath'));
    }
}
