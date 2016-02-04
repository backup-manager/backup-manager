<?php

namespace spec\BackupManager\Shell;

use BackupManager\Shell\InvalidShellCommand;
use BackupManager\Shell\ShellCommand;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ShellCommandSpec extends ObjectBehavior {

    function let() {
        $this->beConstructedWith('test command');
    }

    function it_is_initializable() {
        $this->shouldHaveType(ShellCommand::class);
    }

    function it_should_not_accept_an_invalid_command() {
        $this->beConstructedWith('');
        $this->shouldThrow(InvalidShellCommand::class)->duringInstantiation();
    }

    function it_retrieves_the_command() {
        $this->command()->shouldReturn('test command');
    }
}
