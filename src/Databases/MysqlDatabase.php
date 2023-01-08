<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Databases;

use function array_filter;
use function escapeshellarg;
use function implode;
use function sprintf;
use function trim;

/** @psalm-immutable */
final class MysqlDatabase implements Database
{
    /** @param array<int, string> $ignoreTables */

    /** @param array<int, string> $onlyTables */
    public function __construct(
        private readonly string $host,
        private readonly string $port,
        private readonly string $user,
        private readonly string $password,
        private readonly string $database,
        private readonly bool $singleTransaction = false,
        private readonly bool $ssl = false,
        private readonly array $ignoreTables = [],
        private readonly array $onlyTables = [],
    ) {
    }

    /** @param array<int, string> $onlyTables */
    public function withOnlyTable(array $onlyTables): self
    {
        return new self(
            $this->host,
            $this->port,
            $this->user,
            $this->password,
            $this->database,
            $this->singleTransaction,
            $this->ssl,
            $this->ignoreTables,
            $onlyTables,
        );
    }

    /** @param array<int, string> $ignoreTables */
    public function withIgnoreTable(array $ignoreTables): self
    {
        return new self(
            $this->host,
            $this->port,
            $this->user,
            $this->password,
            $this->database,
            $this->singleTransaction,
            $this->ssl,
            $ignoreTables,
            $this->onlyTables,
        );
    }

    public function getDumpDataCommandLine(string $path): string
    {
        return sprintf(
            'mysqldump %s %s > %s',
            self::addConnectionInformation([
                '--routines',
                '--no-create-info',
                $this->singleTransaction ? '--single-transaction' : '',
                $this->ssl ? '--ssl' : '',
                ...$this->getIgnoreTableParameter(),
            ]),
            $this->getDbAndTables(),
            escapeshellarg($path),
        );
    }

    public function getDumpStructCommandLine(string $path): string
    {
        return sprintf(
            'mysqldump %s %s > %s',
            self::addConnectionInformation([
                '--routines',
                '--no-data',
                $this->singleTransaction ? '--single-transaction' : '',
                $this->ssl ? '--ssl' : '',
            ]),
            $this->getDbAndTables(),
            escapeshellarg($path),
        );
    }

    private function getDbAndTables(): string
    {
        return implode(' ', [
            escapeshellarg($this->database),
            ...$this->getOnlyTable(),
        ]);
    }

    public function getRestoreCommandLine(string $path): string
    {
        return sprintf(
            'mysql %s %s -e "source %s"',
            self::addConnectionInformation([$this->ssl ? '--ssl' : '']),
            escapeshellarg($this->database),
            $path,
        );
    }

    /**
     * @return iterable<int, string>
     * @psalm-return iterable<int, string>
     */
    private function getIgnoreTableParameter(): iterable
    {
        foreach ($this->ignoreTables as $table) {
            yield sprintf('--ignore-table=%s', escapeshellarg($this->database . '.' . $table));
        }
    }

    /**
     * @return iterable<int, string>
     * @psalm-return iterable<int, string>
     */
    private function getOnlyTable(): iterable
    {
        foreach ($this->onlyTables as $table) {
            yield sprintf('%s', escapeshellarg($table));
        }
    }

    /**
     * @param array<int|string, string> $value
     *
     * @return array<int|string, string>
     */
    private static function removeEmptyValue(array $value): array
    {
        return array_filter($value, static fn (string $value) => trim($value) !== '');
    }

    /** @param array<int, string> $params */
    private function addConnectionInformation(array $params): string
    {
        $data = ['host' => $this->host, 'port' => $this->port, 'user' => $this->user, 'password' => $this->password];

        foreach (self::removeEmptyValue($data) as $key => $value) {
            $params[] = sprintf('--%s=%s', $key, escapeshellarg($value));
        }

        return implode(' ', self::removeEmptyValue($params));
    }
}
