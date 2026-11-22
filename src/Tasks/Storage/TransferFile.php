<?php

declare(strict_types=1);

namespace BackupManager\Tasks\Storage;

use BackupManager\Tasks\Task;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;

final class TransferFile implements Task
{
    public function __construct(
        private readonly FilesystemOperator $sourceFilesystem,
        private readonly string $sourcePath,
        private readonly FilesystemOperator $destinationFilesystem,
        private readonly string $destinationPath,
    ) {
    }

    /**
     * @throws FilesystemException
     */
    #[\Override]
    public function execute(): void
    {
        $stream = $this->sourceFilesystem->readStream($this->sourcePath);

        try {
            $this->destinationFilesystem->writeStream($this->destinationPath, $stream);
        } finally {
            // Some adapters close the stream themselves.
            /** @psalm-suppress RedundantConditionGivenDocblockType */
            if (is_resource($stream)) {
                fclose($stream);
            }
        }
    }
}
