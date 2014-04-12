<?php

use BigName\BackupManager\Config\Config;
use BigName\BackupManager\Databases\DatabaseProvider;
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
        $this->assertInstanceOf('BigName\BackupManager\Databases\DatabaseProvider', $provider);
    }

    public function test_can_create_database()
    {
        $provider = new DatabaseProvider(new Config('tests/config/database.php'));
        $database = $provider->get('development');
        $this->assertInstanceOf('BigName\BackupManager\Databases\MysqlDatabase', $database);
    }

    public function test_unsupported_database_exception()
    {
        $this->setExpectedException('BigName\BackupManager\Databases\DatabaseTypeNotSupported');
        $provider = new DatabaseProvider(new Config('tests/config/database.php'));
        $provider->get('unsupported');
    }

    public function test_receive_null_object()
    {
        $provider = new DatabaseProvider(new Config('tests/config/database.php'));
        $this->assertInstanceOf('BigName\BackupManager\Databases\NullDatabase', $provider->get('null'));
    }

    public function test_can_get_available_providers()
    {
        $provider = new DatabaseProvider(new Config('tests/config/database.php'));
        $this->assertEquals(['development', 'unsupported', 'null'], $provider->getAvailableProviders());
    }
}
