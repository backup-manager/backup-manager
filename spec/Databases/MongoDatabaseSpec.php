<?php

namespace spec\BackupManager\Databases;

use BackupManager\Config\Config;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MongoDatabaseSpec extends ObjectBehavior {

    function it_is_initializable() {
        $this->shouldHaveType('BackupManager\Databases\MongoDatabase');
    }

    function it_should_recognize_its_type_with_case_insensitivity() {
        foreach (['mongodb', 'MongoDB', 'MONGODB'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    function it_should_generate_a_valid_database_dump_command() {
        $this->configure();
        $this->getDumpCommandLine('outputPath')->shouldBe("mongodump --quiet -h 'foo':'27017' -u 'bar' -p 'baz' -d 'test' -o 'outputPath' --authenticationDatabase 'admin' && tar -zcf 'mongodump.tar.gz' 'outputPath' && find 'outputPath/.' ! -name 'mongodump.tar.gz' -type d -exec rm -f -r {} +");
    }

    private function configure() {
        $config = Config::fromPhpFile('spec/configs/database.php');
        $this->setConfig($config->get('development'));
    }
}
