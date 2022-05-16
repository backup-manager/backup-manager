<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Tests\Filesystems;

use Fezfez\BackupManager\Filesystems\BackupManagerRessource;
use Fezfez\BackupManager\Filesystems\NotARessource;
use PHPUnit\Framework\TestCase;

use function fopen;
use function fwrite;
use function rewind;
use function stream_get_contents;

class BackupManagerRessourceTest extends TestCase
{
    public function testFail(): void
    {
        self::expectException(NotARessource::class);

        new BackupManagerRessource('toto');
    }

    public function testOk(): void
    {
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, 'toto');
        rewind($stream);

        $sUT = new BackupManagerRessource($stream);

        self::assertSame('toto', stream_get_contents($sUT->getResource()));
    }
}
