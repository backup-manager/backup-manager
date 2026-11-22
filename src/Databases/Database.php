<?php

declare(strict_types=1);

namespace BackupManager\Databases;

interface Database
{
    public function handles(?string $type): bool;

    /**
     * @param array<string, mixed> $config
     */
    public function setConfig(array $config): void;

    public function getDumpCommandLine(string $outputPath): string;

    public function getRestoreCommandLine(string $inputPath): string;
}
