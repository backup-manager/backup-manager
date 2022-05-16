<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Tests\Tasks\Database;

use Fezfez\BackupManager\Databases\Database;
use Fezfez\BackupManager\ShellProcessing\ShellProcessor;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class RestoreDatabaseSpec extends TestCase
{
    public function testExecuteTheDatabaseRestoreCommand(Database $database, ShellProcessor $shellProcessor): void
    {
        $database->getRestoreCommandLine('path')->willReturn('restore path');
        $shellProcessor->process(Argument::any())->shouldBeCalled();

        $this->beConstructedWith($database, 'path', $shellProcessor);
        $this->execute();
    }
}
