<?php

use BigName\BackupManager\Config\Config;
use BigName\BackupManager\Databases\PostgresqlDatabase;
use Mockery as m;

class PostgresqlDatabaseTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $postgres = $this->getDatabase();
        $this->assertInstanceOf('BigName\BackupManager\Databases\PostgresqlDatabase', $postgres);
    }

    public function test_get_dump_command()
    {
        $config = new Config('tests/config/database.php');
        $postgres = $this->getDatabase();
        $postgres->setConfig($config->get('production'));
        $this->assertEquals("pg_dump --host='foo' --port='3306' --username='bar' 'test' -f 'outputPath'", $postgres->getDumpCommandLine('outputPath'));
    }

    public function test_get_restore_command()
    {
        $config = new Config('tests/config/database.php');
        $postgres = $this->getDatabase();
        $postgres->setConfig($config->get('production'));
        $this->assertEquals("psql --host='foo' --port='3306' --user='bar' 'test' -f 'outputPath'", $postgres->getRestoreCommandLine('outputPath'));
    }

    /**
     * @return MysqlDatabase
     */
    private function getDatabase()
    {
        $database = new PostgresqlDatabase;
        return $database;
    }
}
