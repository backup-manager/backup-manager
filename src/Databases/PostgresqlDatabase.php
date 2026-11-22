<?php

declare(strict_types=1);

namespace BackupManager\Databases;

final class PostgresqlDatabase implements Database
{
    /** @var array<string, mixed> */
    private array $config = [];

    #[\Override]
    public function handles(?string $type): bool
    {
        return in_array(strtolower($type ?? ''), ['postgresql', 'postgres', 'pgsql'], true);
    }

    #[\Override]
    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    #[\Override]
    public function getDumpCommandLine(string $outputPath): string
    {
        return sprintf(
            'PGPASSWORD=%s pg_dump --clean --host=%s --port=%s --username=%s %s -f %s',
            escapeshellarg((string) $this->config['pass']),
            escapeshellarg((string) $this->config['host']),
            escapeshellarg((string) $this->config['port']),
            escapeshellarg((string) $this->config['user']),
            escapeshellarg((string) $this->config['database']),
            escapeshellarg($outputPath)
        );
    }

    #[\Override]
    public function getRestoreCommandLine(string $inputPath): string
    {
        return sprintf(
            'PGPASSWORD=%s psql --host=%s --port=%s --user=%s %s -f %s',
            escapeshellarg((string) $this->config['pass']),
            escapeshellarg((string) $this->config['host']),
            escapeshellarg((string) $this->config['port']),
            escapeshellarg((string) $this->config['user']),
            escapeshellarg((string) $this->config['database']),
            escapeshellarg($inputPath)
        );
    }
}
