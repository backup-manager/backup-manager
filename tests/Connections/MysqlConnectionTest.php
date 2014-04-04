<?php

use BigName\DatabaseBackup\Connections\MysqlConnection;
use Mockery as m;

class MysqlConnectionTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test()
    {
        $mysql = new MysqlConnection('host', 'port', 'username', 'password', 'database');

        $this->assertInstanceOf('BigName\DatabaseBackup\Connections\MysqlConnection', $mysql);
        $this->assertEquals('host', $mysql->host);
        $this->assertEquals('port', $mysql->port);
        $this->assertEquals('username', $mysql->username);
        $this->assertEquals('password', $mysql->password);
        $this->assertEquals('database', $mysql->database);
    }
}
