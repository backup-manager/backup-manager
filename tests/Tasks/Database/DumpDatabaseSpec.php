<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Tests\Tasks\Database;

use Fezfez\BackupManager\Databases\Database;
use Fezfez\BackupManager\ShellProcessing\ShellProcessor;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class DumpDatabaseSpec extends TestCase
{
    public function testExecuteTheDatabaseDumpCommand(Database $database, ShellProcessor $shellProcessor): void
    {
        $database->getDumpCommandLine('path')->willReturn('dump path');
        $shellProcessor->process(Argument::any())->shouldBeCalled();

        $this->beConstructedWith($database, 'path', $shellProcessor);
        $this->execute();
    }
}
