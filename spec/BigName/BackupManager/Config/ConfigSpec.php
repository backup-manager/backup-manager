<?php

namespace spec\BigName\BackupManager\Config;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigSpec extends ObjectBehavior
{
    function let()
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith([]);
    }

    function it_is_initializable()
    {
        /** @noinspection PhpParamsInspection */
        $this->shouldHaveType('BigName\BackupManager\Config\Config');
    }

    function it_should_initialize_from_a_php_file()
    {
        $this->beConstructedThrough('BigName\BackupManager\Config\Config::fromPhpFile', ['spec/configs/keys.php']);
        $this->getItems()->shouldBe(['config', 'file', 'items']);
    }

    function it_should_throw_an_exception_if_php_file_isnt_found()
    {
        $this->shouldThrow('BigName\BackupManager\Config\ConfigFileNotFound')->during('fromPhpFile', ['nonexistent-file']);
    }

    function it_should_return_configuration_fields()
    {
        $this->constructFromStorageFile();
        $this->get('local', 'type')->shouldBe('Local');
        $this->get('s3', 'type')->shouldBe('AwsS3');
    }

    function it_should_return_an_entire_connection_config()
    {
        $this->constructFromStorageFile();
        $this->get('local')->shouldBe(['type' => 'Local', 'root' => '/']);
    }

    function it_should_throw_an_exception_when_connection_config_not_found()
    {
        $this->constructFromStorageFile();
        $this->shouldThrow('BigName\BackupManager\Config\ConfigNotFoundForConnection')->during('get', ['baz']);
    }

    function it_should_throw_an_exception_when_config_not_found()
    {
        $this->constructFromStorageFile();
        $this->shouldThrow('BigName\BackupManager\Config\ConfigFieldNotFound')->during('get', ['local', 'foo']);
    }

    private function constructFromStorageFile()
    {
        $this->beConstructedThrough('BigName\BackupManager\Config\Config::fromPhpFile', ['spec/configs/storage.php']);
    }
}
