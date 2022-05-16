<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Tests\Procedures;

use Fezfez\BackupManager\Tasks\Task;
use PHPUnit\Framework\TestCase;

class SequenceSpec extends TestCase
{
    public function testExecuteAProgrammedSequenceOfTasks(Task $taskOne, Task $taskTwo): void
    {
        $taskOne->execute()->shouldBeCalled();
        $taskTwo->execute()->shouldBeCalled();

        $this->add($taskOne);
        $this->add($taskTwo);

        $this->execute();
    }
}
