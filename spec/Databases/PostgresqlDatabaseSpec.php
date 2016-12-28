<?php

namespace spec\BackupManager\Databases;

use BackupManager\Config\Config;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PostgresqlDatabaseSpec extends ObjectBehavior {

    function it_is_initializable() {
        $this->shouldHaveType('BackupManager\Databases\PostgresqlDatabase');
    }

    function it_should_recognize_its_type_with_case_insensitivity() {
        foreach (['postgresql', 'PostGRESql', 'POSTGRESQL'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    function it_should_generate_a_valid_database_dump_command() {
        $this->configure();
        $this->getDumpCommandLine('outputPath')->shouldBe("PGPASSWORD='baz' pg_dump --clean --host='foo' --port='3306' --username='bar' 'test' -f 'outputPath'");
    }

    function it_should_generate_a_valid_database_restore_command() {
        $this->configure();
        $this->getRestoreCommandLine('outputPath')->shouldBe("PGPASSWORD='baz' psql --host='foo' --port='3306' --user='bar' 'test' -f 'outputPath'");
    }

    private function configure() {
        $config = Config::fromPhpFile('spec/configs/database.php');
        $this->setConfig($config->get('development'));
    }
}
