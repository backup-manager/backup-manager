<?php

namespace spec\BackupManager\ShellProcessing;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Process\Process;

class ShellProcessorSpec extends ObjectBehavior {

    function it_is_initializable(Process $process) {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($process);
        $this->shouldHaveType('BackupManager\ShellProcessing\ShellProcessor');
    }

    function it_should_execute_a_command_line_process(Process $process) {
        $process->setCommandLine('foo')->shouldBeCalled();
        $process->run()->shouldBeCalled();
        $process->isSuccessful()->willReturn(true);

        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($process);
        $this->process('foo');
    }

    function it_should_throw_an_exception_when_a_process_fails(Process $process) {
        $process->setCommandLine('foo')->shouldBeCalled();
        $process->run()->shouldBeCalled();
        $process->isSuccessful()->willReturn(false);
        $process->getErrorOutput()->shouldBeCalled();

        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($process);
        $this->shouldThrow('BackupManager\ShellProcessing\ShellProcessFailed')->during('process', ['foo']);
    }

    function it_should_not_process_empty_commands(Process $process) {
        $process->setCommandLine('')->shouldNotBeCalled();
        $process->run()->shouldNotBeCalled();
        $process->isSuccessful()->willReturn(true);

        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($process);
        $this->process('');
    }
}
