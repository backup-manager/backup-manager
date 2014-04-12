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
        $this->assertInstanceOf('BigName\BackupManager\Config\Config', new Config('tests/config/storage.php'));
    }

    public function test_config_file_not_found_exception()
    {
        $this->setExpectedException('BigName\BackupManager\Config\ConfigFileNotFound');
        new Config('foo');
    }

    public function test_can_get_field()
    {
        $config = new Config('tests/config/storage.php');
        $this->assertEquals('Local', $config->get('local', 'type'));
        $this->assertEquals('AwsS3', $config->get('s3', 'type'));
    }

    public function test_can_get_whole_connection_configuration()
    {
        $config = new Config('tests/config/storage.php');
        $this->assertEquals(['type' => 'Local', 'working-path' => '/'], $config->get('local'));
    }

    public function test_config_not_found_for_connection_exception()
    {
        $this->setExpectedException('BigName\BackupManager\Config\ConfigNotFoundForConnection');
        $config = new Config('tests/config/storage.php');
        $config->get('foo');
    }

    public function test_config_field_not_found_exception()
    {
        $this->setExpectedException('BigName\BackupManager\Config\ConfigFieldNotFound');
        $config = new Config('tests/config/storage.php');
        $config->get('local', 'foo');
    }

    public function test_can_get_config_items()
    {
        $config = new Config('tests/config/keys.php');
        $this->assertEquals(['config', 'file', 'items'], $config->getItems());
    }
}
