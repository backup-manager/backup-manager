<?php

use BigName\DatabaseBackup\Connections\MysqlConnection;
use Mockery as m;

class MysqlConnectionTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testConnection()
    {
        $connection = new MysqlConnection('host', 'port', 'username', 'password', 'database');

        $this->assertInstanceOf('BigName\DatabaseBackup\Connections\MysqlConnection', $connection);
        $this->assertEquals('host', $connection->host);
        $this->assertEquals('port', $connection->port);
        $this->assertEquals('username', $connection->username);
        $this->assertEquals('password', $connection->password);
        $this->assertEquals('database', $connection->database);
    }
}
