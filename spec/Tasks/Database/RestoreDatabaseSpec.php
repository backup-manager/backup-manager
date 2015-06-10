<?php

namespace spec\BackupManager\Tasks\Database;

use BackupManager\Databases\Database;
use BackupManager\ShellProcessing\ShellProcessor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RestoreDatabaseSpec extends ObjectBehavior {

    function it_is_initializable(Database $database, ShellProcessor $shellProcessor) {
        $this->beConstructedWith($database, 'path', $shellProcessor);
        $this->shouldHaveType('BackupManager\Tasks\Database\RestoreDatabase');
    }

    function it_should_execute_the_database_restore_command(Database $database, ShellProcessor $shellProcessor) {
        $database->getRestoreCommandLine('path')->willReturn('restore path');
        $shellProcessor->process('restore path')->shouldBeCalled();

        $this->beConstructedWith($database, 'path', $shellProcessor);
        $this->execute();
    }
}
