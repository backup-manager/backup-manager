<?php

use BigName\BackupManager\Databases\MysqlDatabase;
use Mockery as m;

class MysqlDatabaseTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $mysql = new MysqlDatabase([]);
        $this->assertInstanceOf('BigName\BackupManager\Databases\MysqlDatabase', $mysql);
    }

    public function test_get_dump_command()
    {
        $mysql = new MysqlDatabase([
            'host' => 'host',
            'port' => '3306',
            'user' => 'user',
            'pass' => 'password',
            'database' => 'database',
        ]);
        $this->assertEquals("mysqldump --host='host' --port='3306' --user='user' --password='password' 'database' > 'outputPath'", $mysql->getDumpCommandLine('outputPath'));
    }

    public function test_get_restore_command()
    {
        $mysql = new MysqlDatabase([
            'host' => 'host',
            'port' => '3306',
            'user' => 'user',
            'pass' => 'password',
            'database' => 'database',
        ]);
        $this->assertEquals("mysql --host='host' --port='3306' --user='user' --password='password' 'database' -e \"source outputPath;\"", $mysql->getRestoreCommandLine('outputPath'));
    }
}
