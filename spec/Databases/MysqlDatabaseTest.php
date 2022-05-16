<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Tests\Databases;

use Fezfez\BackupManager\Databases\MysqlDatabase;
use PHPUnit\Framework\TestCase;

class MysqlDatabaseTest extends TestCase
{
    public function testGenerateAValidDatabaseDumpCommand(): void
    {
        $sUT = new MysqlDatabase(
            'foo',
            '3306',
            'bar',
            'baz',
            'test'
        );

        self::assertSame("mysqldump --routines --host='foo' --port='3306' --user='bar' --password='baz' 'test' > 'outputPath'", $sUT->getDumpCommandLine('outputPath'));
    }

    public function testGenerateAValidDatabaseDumpCommandWithEmptyPassword(): void
    {
        $sUT = new MysqlDatabase(
            'foo',
            '3306',
            'bar',
            '',
            'test'
        );

        self::assertSame("mysqldump --routines --host='foo' --port='3306' --user='bar' 'test' > 'outputPath'", $sUT->getDumpCommandLine('outputPath'));
    }

    public function testGenerateAValidDatabaseDumpCommandWithSsl(): void
    {
        $sUT = new MysqlDatabase(
            'foo',
            '3306',
            'bar',
            'baz',
            'test',
            true,
            true,
            ['tutu'],
            ['test']
        );

        self::assertSame("mysqldump --routines --single-transaction --ssl --ignore-table='test.test' tutu --host='foo' --port='3306' --user='bar' --password='baz' 'test' > 'outputPath'", $sUT->getDumpCommandLine('outputPath'));
    }

    public function testGenerateAValidDatabaseDumpCommandWithSingleTransaction(): void
    {
        $sUT = new MysqlDatabase(
            'foo',
            '3306',
            'bar',
            'baz',
            'test',
            true
        );

        self::assertSame("mysqldump --routines --single-transaction --host='foo' --port='3306' --user='bar' --password='baz' 'test' > 'outputPath'", $sUT->getDumpCommandLine('outputPath'));
    }

    public function testGenerateAValidDatabaseRestoreCommand(): void
    {
        $sUT = new MysqlDatabase(
            'foo',
            '3306',
            'bar',
            'baz',
            'test'
        );
        self::assertSame("mysql --host='foo' --port='3306' --user='bar' --password='baz' 'test' -e \"source outputPath\"", $sUT->getRestoreCommandLine('outputPath'));
    }

    public function testGenerateAValidDatabaseRestoreCommandWithSsl(): void
    {
        $sUT = new MysqlDatabase(
            'foo',
            '3306',
            'bar',
            'baz',
            'test',
            true,
            true,
            ['toto'],
            ['test']
        );
        self::assertSame("mysql --ssl --host='foo' --port='3306' --user='bar' --password='baz' 'test' -e \"source outputPath\"", $sUT->getRestoreCommandLine('outputPath'));
    }
}
