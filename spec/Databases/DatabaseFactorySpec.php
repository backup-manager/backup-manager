<?php

namespace spec\BackupManager\Databases;

use BackupManager\Config\Config;
use BackupManager\Databases\DatabaseFactory;
use BackupManager\Databases\MysqlDatabase;
use BackupManager\Databases\PostgresqlDatabase;
use BackupManager\Shell\ShellProcessor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DatabaseFactorySpec extends ObjectBehavior {

    function let(ShellProcessor $shell, Config $config) {
        $this->beConstructedWith($shell, $config);
    }

    function it_is_initializable() {
        $this->shouldHaveType(DatabaseFactory::class);
    }

    function it_can_make_a_mysql_database() {
        $this->make('mysql')->shouldBeAnInstanceOf(MysqlDatabase::class);
    }

    function it_can_make_a_postgresql_database() {
        $this->make('postgresql')->shouldBeAnInstanceOf(PostgresqlDatabase::class);
    }
}
