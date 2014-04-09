<?php

use BigName\DatabaseBackup\Config\Config;
use BigName\DatabaseBackup\Databases\DatabaseProvider;
use Mockery as m;

class DatabaseProviderTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $provider = new DatabaseProvider(new Config('tests/config/database.php'));
        $this->assertInstanceOf('BigName\DatabaseBackup\Databases\DatabaseProvider', $provider);
    }

    public function test_can_create_database()
    {
        $provider = new DatabaseProvider(new Config('tests/config/database.php'));
        $database = $provider->get('development');
        $this->assertInstanceOf('BigName\DatabaseBackup\Databases\MysqlDatabase', $database);
    }

    public function test_unsupported_database_exception()
    {
        $this->setExpectedException('BigName\DatabaseBackup\Databases\DatabaseTypeNotSupported');
        $provider = new DatabaseProvider(new Config('tests/config/database.php'));
        $provider->get('unsupported');
    }

    public function test_receive_null_object()
    {
        $provider = new DatabaseProvider(new Config('tests/config/database.php'));
        $this->assertInstanceOf('BigName\DatabaseBackup\Databases\NullDatabase', $provider->get('null'));
    }
}
