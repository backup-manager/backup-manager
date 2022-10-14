<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Tests\Databases;

use Fezfez\BackupManager\Databases\PostgresqlDatabase;
use PHPUnit\Framework\TestCase;

final class PostgresqlDatabaseTest extends TestCase
{
    public function testGenerateAValidDatabaseDumpCommand(): void
    {
        $sUT = new PostgresqlDatabase(
            'foo',
            '3306',
            'bar',
            'baz',
            'test',
        );

        self::assertSame("PGPASSWORD='baz' pg_dump --clean --host='foo' --port='3306' --username='bar' 'test' -f 'outputPath'", $sUT->getDumpCommandLine('outputPath'));
    }

    public function testGenerateAValidDatabaseRestoreCommand(): void
    {
        $sUT = new PostgresqlDatabase(
            'foo',
            '3306',
            'bar',
            'baz',
            'test',
        );
        self::assertSame("PGPASSWORD='baz' psql --host='foo' --port='3306' --user='bar' 'test' -f 'outputPath'", $sUT->getRestoreCommandLine('outputPath'));
    }
}
