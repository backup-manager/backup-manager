<?php

namespace spec\BackupManager\Databases;

use BackupManager\Config\Config;
use BackupManager\Databases\MysqlDatabase;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DatabaseProviderSpec extends ObjectBehavior {

    function let() {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith(Config::fromPhpFile('spec/configs/database.php'));
    }

    function it_is_initializable() {
        $this->shouldHaveType('BackupManager\Databases\DatabaseProvider');
    }

    function it_should_provide_requested_databases_by_name() {
        $this->add(new MysqlDatabase);
        $this->get('development')->shouldHaveType('BackupManager\Databases\MysqlDatabase');
    }

    function it_should_throw_an_exception_if_a_database_is_unsupported() {
        $this->shouldThrow('BackupManager\Databases\DatabaseTypeNotSupported')->during('get', ['unsupported']);
    }

    function it_should_provide_a_list_of_available_databases() {
        $this->getAvailableProviders()->shouldBe(['development', 'production', 'unsupported', 'null']);
    }
}
