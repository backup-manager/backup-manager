<?php

namespace spec\BackupManager\Config;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigSpec extends ObjectBehavior {

    function let() {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith([]);
    }

    function it_is_initializable() {
        /** @noinspection PhpParamsInspection */
        $this->shouldHaveType('BackupManager\Config\Config');
    }

    function it_should_initialize_from_a_php_configuration() {
        $this->beConstructedThrough('fromPhpFile', ['spec/configs/keys.php']);
        $this->getItems()->shouldBe(['config', 'file', 'items']);
    }

    function it_should_throw_an_exception_if_the_php_configuration_isnt_found() {
        $this->shouldThrow('BackupManager\Config\ConfigFileNotFound')->during('fromPhpFile', ['nonexistent-file']);
    }

    function it_should_return_requested_configuration_fields() {
        $this->constructFromStorageFile();
        $this->get('local', 'type')->shouldBe('Local');
        $this->get('s3', 'type')->shouldBe('AwsS3');
    }

    function it_should_return_an_entire_requested_connection_configuration() {
        $this->constructFromStorageFile();
        $this->get('local')->shouldBe(['type' => 'Local', 'root' => '/tmp']);
    }

    function it_should_throw_an_exception_when_a_connection_configuration_is_not_found() {
        $this->constructFromStorageFile();
        $this->shouldThrow('BackupManager\Config\ConfigNotFoundForConnection')->during('get', ['baz']);
    }

    function it_should_throw_an_exception_when_a_configuration_field_is_not_found() {
        $this->constructFromStorageFile();
        $this->shouldThrow('BackupManager\Config\ConfigFieldNotFound')->during('get', ['local', 'foo']);
    }

    private function constructFromStorageFile() {
        $this->beConstructedThrough('fromPhpFile', ['spec/configs/storage.php']);
    }
}
