<?php

use BigName\DatabaseBackup\Commands\Database\Mysql\RestoreDatabase;
use BigName\DatabaseBackup\Connections\MysqlConnection;
use Mockery as m;

class RestoreDatabaseTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $shell = m::mock('BigName\DatabaseBackup\ShellProcessing\ShellProcessor');
        $mysql = new MysqlConnection('host', 'port', 'username', 'password', 'database');

        /** @noinspection PhpParamsInspection */
        $Restore = new RestoreDatabase($mysql, 'outputPath', $shell);
        
        $this->assertInstanceOf('BigName\DatabaseBackup\Commands\Database\Mysql\RestoreDatabase', $Restore);
    }

    public function test_generates_correct_command()
    {
        $shell = m::mock('BigName\DatabaseBackup\ShellProcessing\ShellProcessor');
        $shell->shouldReceive('process')->with("mysql --host='host' --port='port' --user='username' --password='password' 'database' -e \"source outputPath;\"")->once();

        $mysql = new MysqlConnection('host', 'port', 'username', 'password', 'database');

        /** @noinspection PhpParamsInspection */
        $Restore = new RestoreDatabase($mysql, 'outputPath', $shell);
        $Restore->execute();
    }
}
