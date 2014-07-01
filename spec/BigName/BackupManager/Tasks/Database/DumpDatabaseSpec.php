<?php

namespace spec\BigName\BackupManager\Tasks\Database;

use BigName\BackupManager\Databases\Database;
use BigName\BackupManager\ShellProcessing\ShellProcessor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DumpDatabaseSpec extends ObjectBehavior
{
    function it_is_initializable(Database $database, ShellProcessor $shellProcessor)
    {
        $this->beConstructedWith($database, 'path', $shellProcessor);
        $this->shouldHaveType('BigName\BackupManager\Tasks\Database\DumpDatabase');
    }

    function it_should_execute_the_database_dump_command(Database $database, ShellProcessor $shellProcessor)
    {
        $database->getDumpCommandLine('path')->willReturn('dump path');
        $shellProcessor->process('dump path')->shouldBeCalled();

        $this->beConstructedWith($database, 'path', $shellProcessor);
        $this->execute();
    }
}
