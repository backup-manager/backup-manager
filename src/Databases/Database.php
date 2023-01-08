<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Databases;

interface Database
{
    public function getDumpStructCommandLine(string $path): string;

    public function getDumpDataCommandLine(string $path): string;

    public function getRestoreCommandLine(string $path): string;

    /** @param array<int, string> $onlyTables */
    public function withOnlyTable(array $onlyTables): self;

    /** @param array<int, string> $ignoreTables */
    public function withIgnoreTable(array $ignoreTables): self;
}
