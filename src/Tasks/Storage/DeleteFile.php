<?php

declare(strict_types=1);

namespace BackupManager\Tasks\Storage;

use BackupManager\Tasks\Task;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;

final class DeleteFile implements Task
{
    public function __construct(
        private readonly FilesystemOperator $filesystem,
        private readonly string $filePath,
    ) {
    }

    /**
     * @throws FilesystemException
     */
    #[\Override]
    public function execute(): void
    {
        $this->filesystem->delete($this->filePath);
    }
}
