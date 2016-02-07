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

    function it_is_initializable(ShellProcessor $shell, Config $config) {
        $this->beConstructedWith($shell, $config);
        $this->shouldHaveType(DatabaseFactory::class);
    }

    function it_can_make_a_mysql_database(ShellProcessor $shell, Config $config) {
        $config->get(Argument::any())->willReturn(['driver' => 'mysql']);
        $this->beConstructedWith($shell, $config);
        $this->make('mysql')->shouldBeAnInstanceOf(MysqlDatabase::class);
    }

    function it_can_make_a_postgresql_database(ShellProcessor $shell, Config $config) {
        $config->get(Argument::any())->willReturn(['driver' => 'postgresql']);
        $this->beConstructedWith($shell, $config);
        $this->make('postgresql')->shouldBeAnInstanceOf(PostgresqlDatabase::class);
    }
}
