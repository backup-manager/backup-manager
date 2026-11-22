<?php

declare(strict_types=1);

namespace BackupManager\Procedures;

use BackupManager\Compressors\CompressorProvider;
use BackupManager\Config\ConfigFieldNotFound;
use BackupManager\Config\ConfigNotFoundForConnection;
use BackupManager\Databases\DatabaseProvider;
use BackupManager\Filesystems\FilesystemProvider;
use BackupManager\ShellProcessing\ShellProcessor;

abstract class Procedure
{
    public function __construct(
        protected readonly FilesystemProvider $filesystems,
        protected readonly DatabaseProvider $databases,
        protected readonly CompressorProvider $compressors,
        protected readonly ShellProcessor $shellProcessor,
    ) {
    }

    /**
     * @throws ConfigNotFoundForConnection
     * @throws ConfigFieldNotFound
     */
    protected function getWorkingFile(string $name, ?string $filename = null): string
    {
        return sprintf('%s/%s', $this->getRootPath($name), $filename ?? uniqid());
    }

    /**
     * @throws ConfigNotFoundForConnection
     * @throws ConfigFieldNotFound
     */
    protected function getRootPath(string $name): string
    {
        return rtrim((string) $this->filesystems->getConfig($name, 'root'), '/');
    }
}
