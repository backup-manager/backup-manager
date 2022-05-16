<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Databases;

use function escapeshellarg;
use function sprintf;

final class PostgresqlDatabase implements Database
{
    private string $host;
    private string $port;
    private string $user;
    private string $password;
    private string $database;

    public function __construct(
        string $host,
        string $port,
        string $user,
        string $password,
        string $database
    ) {
        $this->host     = $host;
        $this->port     = $port;
        $this->user     = $user;
        $this->password = $password;
        $this->database = $database;
    }

    public function getDumpCommandLine(string $path): string
    {
        return sprintf(
            'PGPASSWORD=%s pg_dump --clean --host=%s --port=%s --username=%s %s -f %s',
            escapeshellarg($this->password),
            escapeshellarg($this->host),
            escapeshellarg($this->port),
            escapeshellarg($this->user),
            escapeshellarg($this->database),
            escapeshellarg($path)
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
            escapeshellarg($path)
        );
    }
}
