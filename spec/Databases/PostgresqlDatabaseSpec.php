<?php

namespace spec\BackupManager\Databases;

use BackupManager\Config\Config;
use BackupManager\Databases\Database;
use BackupManager\Databases\PostgresqlDatabase;
use BackupManager\File;
use BackupManager\Shell\ShellCommand;
use BackupManager\Shell\ShellProcessor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PostgresqlDatabaseSpec extends ObjectBehavior {

    function it_is_initializable(ShellProcessor $shell) {
        $config = new Config(['host' => 'host', 'port' => 'port', 'user' => 'user', 'pass' => 'pass', 'database' => 'database']);
        $this->beConstructedWith($shell, $config);

        $this->shouldHaveType(PostgresqlDatabase::class);
        $this->shouldImplement(Database::class);
    }

    function it_can_dump_the_database(ShellProcessor $shell) {
        $shell->process(new ShellCommand("PGPASSWORD='pass' pg_dump --host='host' --port='port' --username='user' 'database' -f 'file.sql'"))->shouldBeCalled();
        $config = new Config(['host' => 'host', 'port' => 'port', 'user' => 'user', 'pass' => 'pass', 'database' => 'database']);
        $this->beConstructedWith($shell, $config);

        $file = new File('file.sql');
        $this->dump($file);
    }

    function it_can_restore_the_dump(ShellProcessor $shell) {
        $shell->process(new ShellCommand("PGPASSWORD='pass' psql --host='host' --port='port' --user='user' 'database' -f 'file.sql'"))->shouldBeCalled();
        $config = new Config(['host' => 'host', 'port' => 'port', 'user' => 'user', 'pass' => 'pass', 'database' => 'database']);
        $this->beConstructedWith($shell, $config);

        $file = new File('file.sql');
        $this->restore($file);
    }
}
