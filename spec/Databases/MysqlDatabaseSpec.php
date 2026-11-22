<?php

namespace spec\BackupManager\Databases;

use BackupManager\Config\Config;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MysqlDatabaseSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('BackupManager\Databases\MysqlDatabase');
    }

    public function it_should_recognize_its_type_with_case_insensitivity()
    {
        foreach (['mysql', 'MYsql', 'MYSQL', 'pdo_mysql', 'PDO_MYSQL'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    public function it_should_generate_a_valid_database_dump_command()
    {
        $this->configure();
        $this->getDumpCommandLine('outputPath')->shouldBe("mysqldump --routines  --host='foo' --port='3306' --user='bar' --password='baz' 'test' > 'outputPath'");
    }

    public function it_should_generate_a_valid_database_dump_command_with_single_transaction()
    {
        $this->configure('developmentSingleTrans');
        $this->getDumpCommandLine('outputPath')->shouldBe("mysqldump --routines --single-transaction --host='foo' --port='3306' --user='bar' --password='baz' 'test' > 'outputPath'");
    }

    public function it_should_disable_column_statistics_when_configured()
    {
        $this->setConfig([
            'type' => 'mysql',
            'host' => 'foo',
            'database' => 'test',
            'columnStatistics' => false,
        ]);
        $this->getDumpCommandLine('outputPath')->shouldBe("mysqldump --routines --column-statistics=0 --host='foo' 'test' > 'outputPath'");
    }

    public function it_should_dump_only_the_configured_tables()
    {
        $this->setConfig([
            'type' => 'mysql',
            'host' => 'foo',
            'database' => 'test',
            'onlyTables' => ['users', 'orders'],
        ]);
        $this->getDumpCommandLine('outputPath')->shouldBe("mysqldump --routines  --host='foo' 'test' 'users' 'orders' > 'outputPath'");
    }

    public function it_should_generate_a_valid_database_restore_command()
    {
        $this->configure();
        $this->getRestoreCommandLine('outputPath')->shouldBe("mysql --host='foo' --port='3306' --user='bar' --password='baz'  'test' -e \"source outputPath\"");
    }

    public function it_should_connect_through_a_socket_instead_of_host_and_port()
    {
        $this->setConfig([
            'type' => 'mysql',
            'host' => 'foo',
            'port' => '3306',
            'socket' => '/run/mysqld/mysqld.sock',
            'user' => 'bar',
            'pass' => 'baz',
            'database' => 'test',
        ]);
        $this->getDumpCommandLine('outputPath')->shouldBe("mysqldump --routines  --socket='/run/mysqld/mysqld.sock' --user='bar' --password='baz' 'test' > 'outputPath'");
        $this->getRestoreCommandLine('outputPath')->shouldBe("mysql --socket='/run/mysqld/mysqld.sock' --user='bar' --password='baz'  'test' -e \"source outputPath\"");
    }

    private function configure($db = 'development')
    {
        $config = Config::fromPhpFile('spec/configs/database.php');
        $this->setConfig($config->get($db));
    }
}
