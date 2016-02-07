<?php

namespace spec\BackupManager\Config;

use BackupManager\Config\Config;
use BackupManager\Config\ConfigItemDoesNotExist;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigSpec extends ObjectBehavior {

    function let() {
        $this->beConstructedWith([
            'key' => 'value',
            'more' => 'stuff',
            'nesting' => [
                'item' => 'another'
            ]
        ]);
    }

    function it_is_initializable() {
        $this->shouldHaveType(Config::class);
    }

    function it_retrieves_an_item() {
        $this->get('key')->shouldReturn('value');
        $this->get('more')->shouldReturn('stuff');
        $this->get('nesting.item')->shouldReturn('another');
    }

    function it_retrieves_all_items() {
        $this->all()->shouldReturn([
            'key' => 'value',
            'more' => 'stuff',
            'nesting' => [
                'item' => 'another'
            ]
        ]);
    }

    function it_throws_when_retrieving_unknown_item() {
        $this->shouldThrow(ConfigItemDoesNotExist::class)->during('get', ['unknown']);
    }
}
