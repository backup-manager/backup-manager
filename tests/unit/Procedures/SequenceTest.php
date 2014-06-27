<?php

use BigName\BackupManager\Procedures\Sequence;
use Mockery as m;

class SequenceTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $this->assertInstanceOf('BigName\BackupManager\Procedures\Sequence', new Sequence());
    }

    public function test_can_execute_commands()
    {
        $sequence = new Sequence;

        $command = m::mock('BigName\BackupManager\Tasks\Task');
        $command->shouldReceive('execute')->once();

        $command2 = m::mock('BigName\BackupManager\Tasks\Task');
        $command2->shouldReceive('execute')->once();

        $sequence->add($command);
        $sequence->add($command2);

        $sequence->execute();
    }
}
