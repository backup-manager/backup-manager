<?php

namespace spec\BackupManager\Shell;

use BackupManager\Shell\ShellCommand;
use BackupManager\Shell\ShellProcessFailed;
use BackupManager\Shell\ShellProcessor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Process\Process;

class ShellProcessorSpec extends ObjectBehavior {

    function it_is_initializable(Process $process) {
        $this->beConstructedWith($process);
        $this->shouldHaveType(ShellProcessor::class);
    }

    function it_executes_a_shell_command(Process $process) {
        $command = new ShellCommand('test');
        $process->setCommandLine($command->command())->shouldBeCalled();
        $process->setTimeout(null)->shouldBeCalled();
        $process->run()->shouldBeCalled();
        $process->isSuccessful()->willReturn(true);

        $this->beConstructedWith($process);
        $this->process($command);
    }

    function it_throws_when_a_shell_command_fails(Process $process) {
        $command = new ShellCommand('failing');
        $process->setCommandLine($command->command())->shouldBeCalled();
        $process->setTimeout(null)->shouldBeCalled();
        $process->run()->shouldBeCalled();
        $process->isSuccessful()->willReturn(false);
        $process->getErrorOutput()->shouldBeCalled();

        $this->beConstructedWith($process);
        $this->shouldThrow(ShellProcessFailed::class)->during('process', [$command]);
    }
}
