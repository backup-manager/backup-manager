<?php

namespace spec\BigName\BackupManager\Procedures;

use BigName\BackupManager\Tasks\Task;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SequenceSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('BigName\BackupManager\Procedures\Sequence');
    }

    function it_should_execute_a_programmed_sequence_of_tasks(Task $taskOne, Task $taskTwo)
    {
        $taskOne->execute()->shouldBeCalled();
        $taskTwo->execute()->shouldBeCalled();

        $this->add($taskOne);
        $this->add($taskTwo);

        $this->execute();
    }
}
