<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Filesystems\League;

use Fezfez\BackupManager\Filesystems\BackupManagerFilesystemAdapter;
use Fezfez\BackupManager\Filesystems\BackupManagerRessource;
use Fezfez\BackupManager\Filesystems\CantDeleteFile;
use Fezfez\BackupManager\Filesystems\CantReadFile;
use Fezfez\BackupManager\Filesystems\CantWriteFile;
use League\Flysystem\FilesystemOperator;
use Throwable;

use function sprintf;

final class LeagueFilesystemAdapterV3 implements BackupManagerFilesystemAdapter
{
    public function __construct(private readonly FilesystemOperator $fileSysteme)
    {
    }

    public function readStream(string $path): BackupManagerRessource
    {
        try {
            return new BackupManagerRessource($this->fileSysteme->readStream($path));
        } catch (Throwable $exception) {
            throw new CantReadFile(sprintf('cant read file %s', $path), 0, $exception);
        }
    }

    public function writeStream(string $path, BackupManagerRessource $resource): void
    {
        try {
            $this->fileSysteme->writeStream($path, $resource->getResource());
        } catch (Throwable $exception) {
            throw new CantWriteFile(sprintf('cant write file %s', $path), 0, $exception);
        }
    }

    public function delete(string $path): void
    {
        try {
            $this->fileSysteme->delete($path);
        } catch (Throwable $exception) {
            throw new CantDeleteFile(sprintf('cant delete file %s', $path), 0, $exception);
        }
    }
}
