<?php

namespace spec\BackupManager\Databases;

use BackupManager\Config\Config;
use BackupManager\Databases\MysqlDatabase;
use BackupManager\File;
use BackupManager\Shell\ShellCommand;
use BackupManager\Shell\ShellProcessor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MysqlDatabaseSpec extends ObjectBehavior {

    function let(ShellProcessor $shell) {
        $config = new Config(['host' => 'host', 'port' => 'port', 'user' => 'user', 'pass' => 'pass', 'database' => 'database']);
        $this->beConstructedWith($shell, $config);
    }

    function it_is_initializable() {
        $this->shouldHaveType(MysqlDatabase::class);
    }

    function it_can_dump_the_database(ShellProcessor $shell) {
        $shell->process(new ShellCommand("mysqldump --routines --host='host' --port='port' --user='user' --password='pass' 'database' > 'file.sql'"))->shouldBeCalled();
        $config = new Config(['host' => 'host', 'port' => 'port', 'user' => 'user', 'pass' => 'pass', 'database' => 'database']);
        $this->beConstructedWith($shell, $config);

        $file = new File('file.sql');
        $this->dump($file);
    }

    function it_can_restore_the_database(ShellProcessor $shell) {
        $shell->process(new ShellCommand("mysql --host='host' --port='port' --user='user' --password='pass' 'database' -e \"source 'file.sql'\""))->shouldBeCalled();
        $config = new Config(['host' => 'host', 'port' => 'port', 'user' => 'user', 'pass' => 'pass', 'database' => 'database']);
        $this->beConstructedWith($shell, $config);

        $file = new File('file.sql');
        $this->restore($file);
    }
}
