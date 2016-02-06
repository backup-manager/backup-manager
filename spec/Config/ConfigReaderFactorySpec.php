<?php

namespace spec\BackupManager\Config;

use BackupManager\Config\ConfigReaderFactory;
use BackupManager\Config\ConfigReaderTypeDoesNotExist;
use BackupManager\Config\YamlConfigReader;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigReaderFactorySpec extends ObjectBehavior {

    function it_is_initializable() {
        $this->shouldHaveType(ConfigReaderFactory::class);
    }

    function it_can_make_a_yaml_reader() {
        $this->make('yml')->shouldBeAnInstanceOf(YamlConfigReader::class);
    }

    function it_throws_when_trying_to_instantiate_an_unknown_reader() {
        $this->shouldThrow(ConfigReaderTypeDoesNotExist::class)->during('make', ['unknown']);
    }
}
