<?php

namespace spec\BigName\BackupManager\Databases;

use BigName\BackupManager\Config\Config;
use BigName\BackupManager\Databases\MysqlDatabase;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DatabaseProviderSpec extends ObjectBehavior
{
    function let()
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith(Config::fromPhpFile('spec/configs/database.php'));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('BigName\BackupManager\Databases\DatabaseProvider');
    }

    function it_should_deliver_database_by_name()
    {
        $this->add(new MysqlDatabase);
        $this->get('development')->shouldHaveType('BigName\BackupManager\Databases\MysqlDatabase');
    }

    function it_throws_an_exception_if_it_cant_find_the_database()
    {
        $this->shouldThrow('BigName\BackupManager\Databases\DatabaseTypeNotSupported')->during('get', ['unsupported']);
    }

    function it_should_give_a_list_of_available_databases()
    {
        $this->getAvailableProviders()->shouldBe(['development', 'production', 'unsupported', 'null']);
    }
}
