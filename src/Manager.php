<?php

declare(strict_types=1);

namespace BackupManager;

use BackupManager\Compressors\CompressorProvider;
use BackupManager\Databases\DatabaseProvider;
use BackupManager\Filesystems\FilesystemProvider;
use BackupManager\Procedures\BackupProcedure;
use BackupManager\Procedures\RestoreProcedure;
use BackupManager\ShellProcessing\ShellProcessor;

/**
 * Entry point to the backup and restore procedures.
 *
 * You can copy this class into your project and adapt it, so that your
 * application depends on one place and the rest of the system on your copy.
 */
class Manager
{
    public function __construct(
        private readonly FilesystemProvider $filesystems,
        private readonly DatabaseProvider $databases,
        private readonly CompressorProvider $compressors,
    ) {
    }

    public function makeBackup(): BackupProcedure
    {
        return new BackupProcedure($this->filesystems, $this->databases, $this->compressors, $this->getShellProcessor());
    }

    public function makeRestore(): RestoreProcedure
    {
        return new RestoreProcedure($this->filesystems, $this->databases, $this->compressors, $this->getShellProcessor());
    }

    protected function getShellProcessor(): ShellProcessor
    {
        return new ShellProcessor();
    }
}
