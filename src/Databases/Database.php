<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Databases;

interface Database
{
    public function getDumpCommandLine(string $path): string;

    public function getRestoreCommandLine(string $path): string;
}
