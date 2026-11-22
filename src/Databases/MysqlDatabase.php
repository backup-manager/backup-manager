<?php

declare(strict_types=1);

namespace BackupManager\Databases;

final class MysqlDatabase implements Database
{
    /** @var array<string, mixed> */
    private array $config = [];

    #[\Override]
    public function handles(?string $type): bool
    {
        return in_array(strtolower($type ?? ''), ['mysql', 'pdo_mysql'], true);
    }

    #[\Override]
    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    #[\Override]
    public function getDumpCommandLine(string $outputPath): string
    {
        $extras = [];
        if (($this->config['singleTransaction'] ?? false) === true) {
            $extras[] = '--single-transaction';
        }
        if (array_key_exists('ignoreTables', $this->config)) {
            $extras[] = $this->getIgnoreTableParameter();
        }
        if (($this->config['ssl'] ?? false) === true) {
            $extras[] = '--ssl';
        }
        if (($this->config['columnStatistics'] ?? null) === false) {
            // MySQL 8 mysqldump queries column statistics that older servers and MariaDB lack.
            $extras[] = '--column-statistics=0';
        }
        if (!empty($this->config['extraParams'])) {
            $extras[] = $this->config['extraParams'];
        }

        return sprintf(
            'mysqldump --routines %s%s %s > %s',
            implode(' ', $extras),
            $this->getConnectionParameters(),
            escapeshellarg((string) $this->config['database']) . $this->getOnlyTablesArguments(),
            escapeshellarg($outputPath)
        );
    }

    #[\Override]
    public function getRestoreCommandLine(string $inputPath): string
    {
        $extras = [];
        if (($this->config['ssl'] ?? false) === true) {
            $extras[] = '--ssl';
        }

        return sprintf(
            'mysql%s %s %s -e %s',
            $this->getConnectionParameters(),
            implode(' ', $extras),
            escapeshellarg((string) $this->config['database']),
            escapeshellarg('source ' . $inputPath)
        );
    }

    public function getIgnoreTableParameter(): string
    {
        if (!is_array($this->config['ignoreTables']) || count($this->config['ignoreTables']) === 0) {
            return '';
        }

        $db = $this->config['database'];

        return implode(' ', array_map(
            static fn ($table): string => sprintf('--ignore-table=%s', escapeshellarg($db . '.' . $table)),
            $this->config['ignoreTables']
        ));
    }

    private function getConnectionParameters(): string
    {
        $params = '';
        foreach (['host' => 'host', 'port' => 'port', 'user' => 'user', 'pass' => 'password'] as $key => $mysqlParam) {
            if (!empty($this->config[$key])) {
                $params .= sprintf(' --%s=%s', $mysqlParam, escapeshellarg((string) $this->config[$key]));
            }
        }

        return $params;
    }

    /**
     * Table names after the database name restrict the dump to those tables.
     */
    private function getOnlyTablesArguments(): string
    {
        if (empty($this->config['onlyTables']) || !is_array($this->config['onlyTables'])) {
            return '';
        }

        return ' ' . implode(' ', array_map('escapeshellarg', $this->config['onlyTables']));
    }
}
