<?php

use BigName\BackupManager\Config\Config;
use Mockery as m;

class ConfigTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $this->assertInstanceOf('BigName\BackupManager\Config\Config', new Config([]));
    }

    public function test_can_create_from_php_file()
    {
        $config = Config::fromPhpFile('tests/unit/Config/keys.php');
        $this->assertEquals(['config', 'file', 'items'], $config->getItems());
    }

    public function test_config_file_not_found_exception()
    {
        $this->setExpectedException('BigName\BackupManager\Config\ConfigFileNotFound');
        Config::fromPhpFile('foo');
    }

    public function test_can_get_field()
    {
        $config = Config::fromPhpFile('tests/unit/config/storage.php');
        $this->assertEquals('Local', $config->get('local', 'type'));
        $this->assertEquals('AwsS3', $config->get('s3', 'type'));
    }

    public function test_can_get_whole_connection_configuration()
    {
        $config = Config::fromPhpFile('tests/unit/config/storage.php');
        $this->assertEquals(['type' => 'Local', 'root' => '/'], $config->get('local'));
    }

    public function test_config_not_found_for_connection_exception()
    {
        $this->setExpectedException('BigName\BackupManager\Config\ConfigNotFoundForConnection');
        $config = Config::fromPhpFile('tests/unit/config/storage.php');
        $config->get('foo');
    }

    public function test_config_field_not_found_exception()
    {
        $this->setExpectedException('BigName\BackupManager\Config\ConfigFieldNotFound');
        $config = Config::fromPhpFile('tests/unit/config/storage.php');
        $config->get('local', 'foo');
    }
}
