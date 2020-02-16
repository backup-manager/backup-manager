<?php namespace spec\BackupManager\Tasks\Database;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use BackupManager\Databases\Database;
use BackupManager\ShellProcessing\ShellProcessor;

class DumpDatabaseSpec extends ObjectBehavior
{

    function it_is_initializable(Database $database, ShellProcessor $shellProcessor)
    {
        $this->beConstructedWith($database, 'path', $shellProcessor);
        $this->shouldHaveType('BackupManager\Tasks\Database\DumpDatabase');
    }

    function it_should_execute_the_database_dump_command(Database $database, ShellProcessor $shellProcessor)
    {
        $database->getDumpCommandLine('path')->willReturn('dump path');
        $shellProcessor->process(Argument::any())->shouldBeCalled();

        $this->beConstructedWith($database, 'path', $shellProcessor);
        $this->execute();
    }
}
