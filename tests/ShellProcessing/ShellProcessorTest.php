<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Tests\ShellProcessing;

use Fezfez\BackupManager\ShellProcessing\ShellProcessFailed;
use Fezfez\BackupManager\ShellProcessing\ShellProcessor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

final class ShellProcessorTest extends TestCase
{
    public function testExecuteACommandLineProcess(): void
    {
        $process = $this->createMock(Process::class);
        $process->expects($this->once())->method('run');
        $process->expects($this->once())->method('setTimeout')->with(500.0);
        $process->expects($this->once())->method('isSuccessful')->willReturn(true);
        $process->expects($this->never())->method('getErrorOutput');

        $sUT = new ShellProcessor();
        $sUT->__invoke($process);
    }

    public function testThrowAnExceptionWhenAProcessFails(): void
    {
        $process = $this->createMock(Process::class);
        $process->expects($this->once())->method('run');
        $process->expects($this->once())->method('setTimeout')->with(500.0);
        $process->expects($this->once())->method('isSuccessful')->willReturn(false);
        $process->expects($this->once())->method('getErrorOutput')->willReturn('oyoyo');

        $sUT = new ShellProcessor();

        self::expectException(ShellProcessFailed::class);

        $sUT->__invoke($process);
    }
}
