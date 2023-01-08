<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Databases;

use function escapeshellarg;
use function sprintf;

final class PostgresqlDatabase implements Database
{
    public function __construct(
        private readonly string $host,
        private readonly string $port,
        private readonly string $user,
        private readonly string $password,
        private readonly string $database,
    ) {
    }

    public function getDumpDataCommandLine(string $path): string
    {
        return sprintf(
            'PGPASSWORD=%s pg_dump --column-inserts --data-only --clean --host=%s --port=%s --username=%s %s -f %s',
            escapeshellarg($this->password),
            escapeshellarg($this->host),
            escapeshellarg($this->port),
            escapeshellarg($this->user),
            escapeshellarg($this->database),
            escapeshellarg($path),
        );
    }

    public function getDumpStructCommandLine(string $path): string
    {
        return sprintf(
            'PGPASSWORD=%s pg_dump --schema-only --section=pre-data --clean --host=%s --port=%s --username=%s %s -f %s',
            escapeshellarg($this->password),
            escapeshellarg($this->host),
            escapeshellarg($this->port),
            escapeshellarg($this->user),
            escapeshellarg($this->database),
            escapeshellarg($path),
        );
    }

    public function getRestoreCommandLine(string $path): string
    {
        return sprintf(
            'PGPASSWORD=%s psql --host=%s --port=%s --user=%s %s -f %s',
            escapeshellarg($this->password),
            escapeshellarg($this->host),
            escapeshellarg($this->port),
            escapeshellarg($this->user),
            escapeshellarg($this->database),
            escapeshellarg($path),
        );
    }
}
