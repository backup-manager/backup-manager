<?php

use BigName\BackupManager\Config\Config;
use BigName\BackupManager\Databases\DatabaseProvider;
use BigName\BackupManager\Databases\MysqlDatabase;
use BigName\BackupManager\Databases\PostgresqlDatabase;
use Mockery as m;

class DatabaseProviderTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $provider = $this->getProvider();
        $this->assertInstanceOf('BigName\BackupManager\Databases\DatabaseProvider', $provider);
    }

    public function test_can_create_database()
    {
        $provider = $this->getProvider();
        $database = $provider->get('development');
        $this->assertInstanceOf('BigName\BackupManager\Databases\MysqlDatabase', $database);
    }

    public function test_unsupported_database_exception()
    {
        $this->setExpectedException('BigName\BackupManager\Databases\DatabaseTypeNotSupported');
        $provider = $this->getProvider();
        $provider->get('unsupported');
    }

    public function test_can_get_available_providers()
    {
        $provider = $this->getProvider();
        $this->assertEquals(['development', 'production', 'unsupported', 'null'], $provider->getAvailableProviders());
    }

    private function getProvider()
    {
        $provider = new DatabaseProvider(Config::fromPhpFile('tests/unit/config/database.php'));
        $provider->add(new MysqlDatabase);
        $provider->add(new PostgresqlDatabase);
        return $provider;
    }
}
