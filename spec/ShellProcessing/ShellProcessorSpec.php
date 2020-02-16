<?php namespace spec\BackupManager\ShellProcessing;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Process\Process;
use BackupManager\ShellProcessing\ShellProcessFailed;

class ShellProcessorSpec extends ObjectBehavior
{
    function it_should_execute_a_command_line_process(Process $process)
    {
        $process->run()->shouldBeCalled();
        $process->setTimeout(null)->shouldBeCalled();
        $process->isSuccessful()->willReturn(true);
        $process->getOutput()->shouldBeCalled();
        
        $this->process($process);
    }

    function it_should_throw_an_exception_when_a_process_fails(Process $process)
    {
        $process->run()->shouldBeCalled();
        $process->setTimeout(null)->shouldBeCalled();
        $process->isSuccessful()->willReturn(false);
        $process->getErrorOutput()->shouldBeCalled();
        
        $this->shouldThrow(ShellProcessFailed::class)->during('process', [$process]);
    }
}
