<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Databases;

interface Database
{
    public function getDumpStructCommandLine(string $path): string;

    public function getDumpDataCommandLine(string $path): string;

    public function getRestoreCommandLine(string $path): string;
}
