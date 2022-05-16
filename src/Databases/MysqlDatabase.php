<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Databases;

use function array_filter;
use function escapeshellarg;
use function implode;
use function sprintf;
use function trim;

class MysqlDatabase implements Database
{
    private string $host;
    private string $port;
    private string $user;
    private string $password;
    private string $database;
    private bool $singleTransaction;
    private bool $ssl;
    /** @var array<int, string> */
    private array $extraParams;
    /** @var array<int, string> */
    private array $ignoreTables;

    /**
     * @param array<int, string> $extraParams
     * @param array<int, string> $ignoreTables
     */
    public function __construct(
        string $host,
        string $port,
        string $user,
        string $password,
        string $database,
        bool $singleTransaction = false,
        bool $ssl = false,
        array $extraParams = [],
        array $ignoreTables = []
    ) {
        $this->host              = $host;
        $this->port              = $port;
        $this->user              = $user;
        $this->password          = $password;
        $this->database          = $database;
        $this->singleTransaction = $singleTransaction;
        $this->ssl               = $ssl;
        $this->extraParams       = $extraParams;
        $this->ignoreTables      = $ignoreTables;
    }

    public function getDumpCommandLine(string $path): string
    {
        return sprintf(
            'mysqldump %s %s > %s',
            self::addConnectionInformation([
                '--routines',
                $this->singleTransaction ? '--single-transaction' : '',
                $this->ssl ? '--ssl' : '',
                ...$this->getIgnoreTableParameter(),
                ...$this->extraParams,
            ]),
            escapeshellarg($this->database),
            escapeshellarg($path)
        );
    }

    public function getRestoreCommandLine(string $path): string
    {
        return sprintf(
            'mysql %s %s -e "source %s"',
            self::addConnectionInformation([$this->ssl ? '--ssl' : '']),
            escapeshellarg($this->database),
            $path
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
     * @param array<int|string, string> $value
     *
     * @return array<int|string, string>
     */
    private static function removeEmptyValue(array $value): array
    {
        return array_filter($value, static fn (string $value) => trim($value) !== '');
    }

    /**
     * @param array<int, string> $params
     */
    private function addConnectionInformation(array $params): string
    {
        $data = ['host' => $this->host, 'port' => $this->port, 'user' => $this->user, 'password' => $this->password];

        foreach (self::removeEmptyValue($data) as $key => $value) {
            $params[] = sprintf('--%s=%s', $key, escapeshellarg($value));
        }

        return implode(' ', self::removeEmptyValue($params));
    }
}
