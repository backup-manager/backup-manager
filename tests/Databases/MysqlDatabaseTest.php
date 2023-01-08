<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Tests\Databases;

use Fezfez\BackupManager\Databases\MysqlDatabase;
use PHPUnit\Framework\TestCase;

final class MysqlDatabaseTest extends TestCase
{
    public function testWith(): void
    {
        $sUT = new MysqlDatabase(
            'foo',
            '3306',
            'bar',
            'baz',
            'test',
        );

        self::assertSame("mysqldump --routines --no-create-info --host='foo' --port='3306' --user='bar' --password='baz' 'test' > 'outputPath'", $sUT->getDumpDataCommandLine('outputPath'));
        self::assertSame("mysqldump --routines --no-data --host='foo' --port='3306' --user='bar' --password='baz' 'test' > 'outputPath'", $sUT->getDumpStructCommandLine('outputPath'));

        $sUT = $sUT->withOnlyTable(['toto']);
        self::assertSame("mysqldump --routines --no-create-info --host='foo' --port='3306' --user='bar' --password='baz' 'test' 'toto' > 'outputPath'", $sUT->getDumpDataCommandLine('outputPath'));
        self::assertSame("mysqldump --routines --no-data --host='foo' --port='3306' --user='bar' --password='baz' 'test' 'toto' > 'outputPath'", $sUT->getDumpStructCommandLine('outputPath'));
        $sUT = $sUT->withIgnoreTable(['tutu']);
        self::assertSame("mysqldump --routines --no-create-info --ignore-table='test.tutu' --host='foo' --port='3306' --user='bar' --password='baz' 'test' 'toto' > 'outputPath'", $sUT->getDumpDataCommandLine('outputPath'));
        self::assertSame("mysqldump --routines --no-data --host='foo' --port='3306' --user='bar' --password='baz' 'test' 'toto' > 'outputPath'", $sUT->getDumpStructCommandLine('outputPath'));
    }

    public function testGenerateAValidDatabaseDumpCommand(): void
    {
        $sUT = new MysqlDatabase(
            'foo',
            '3306',
            'bar',
            'baz',
            'test',
        );

        self::assertSame("mysqldump --routines --no-create-info --host='foo' --port='3306' --user='bar' --password='baz' 'test' > 'outputPath'", $sUT->getDumpDataCommandLine('outputPath'));
        self::assertSame("mysqldump --routines --no-data --host='foo' --port='3306' --user='bar' --password='baz' 'test' > 'outputPath'", $sUT->getDumpStructCommandLine('outputPath'));
    }

    public function testGenerateAValidDatabaseDumpCommandWithEmptyPassword(): void
    {
        $sUT = new MysqlDatabase(
            'foo',
            '3306',
            'bar',
            '   ',
            'test',
        );

        self::assertSame("mysqldump --routines --no-create-info --host='foo' --port='3306' --user='bar' 'test' > 'outputPath'", $sUT->getDumpDataCommandLine('outputPath'));
        self::assertSame("mysqldump --routines --no-data --host='foo' --port='3306' --user='bar' 'test' > 'outputPath'", $sUT->getDumpStructCommandLine('outputPath'));
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
            ['test'],
        );

        self::assertSame("mysqldump --routines --no-create-info --single-transaction --ssl --ignore-table='test.test' --host='foo' --port='3306' --user='bar' --password='baz' 'test' > 'outputPath'", $sUT->getDumpDataCommandLine('outputPath'));
        self::assertSame("mysqldump --routines --no-data --single-transaction --ssl --host='foo' --port='3306' --user='bar' --password='baz' 'test' > 'outputPath'", $sUT->getDumpStructCommandLine('outputPath'));
    }

    public function testGenerateAValidDatabaseDumpCommandWithSingleTransaction(): void
    {
        $sUT = new MysqlDatabase(
            'foo',
            '3306',
            'bar',
            'baz',
            'test',
            true,
        );

        self::assertSame("mysqldump --routines --no-create-info --single-transaction --host='foo' --port='3306' --user='bar' --password='baz' 'test' > 'outputPath'", $sUT->getDumpDataCommandLine('outputPath'));
        self::assertSame("mysqldump --routines --no-data --single-transaction --host='foo' --port='3306' --user='bar' --password='baz' 'test' > 'outputPath'", $sUT->getDumpStructCommandLine('outputPath'));
    }

    public function testGenerateAValidDatabaseRestoreCommand(): void
    {
        $sUT = new MysqlDatabase(
            'foo',
            '3306',
            'bar',
            'baz',
            'test',
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
        );
        self::assertSame("mysql --ssl --host='foo' --port='3306' --user='bar' --password='baz' 'test' -e \"source outputPath\"", $sUT->getRestoreCommandLine('outputPath'));
    }
}
