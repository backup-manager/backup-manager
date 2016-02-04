<?php

namespace spec\BackupManager\Config;

use BackupManager\Config\Config;
use BackupManager\Config\ConfigItemNotFound;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigSpec extends ObjectBehavior {

    function let() {
        $this->beConstructedWith([
            'key' => 'value',
            'more' => 'stuff'
        ]);
    }

    function it_is_initializable() {
        $this->shouldHaveType(Config::class);
    }

    function it_retrieves_an_item() {
        $this->get('key')->shouldReturn('value');
        $this->get('more')->shouldReturn('stuff');
    }

    function it_retrieves_all_items() {
        $this->all()->shouldReturn([
            'key' => 'value',
            'more' => 'stuff'
        ]);
    }

    function it_throws_when_tries_retrieving_an_unknown_item() {
        $this->shouldThrow(ConfigItemNotFound::class)->during('get', ['unknown']);
    }
}
