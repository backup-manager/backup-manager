<?php

declare(strict_types=1);

namespace BackupManager\Procedures;

use BackupManager\Compressors\CompressorTypeNotSupported;
use BackupManager\Config\ConfigFieldNotFound;
use BackupManager\Config\ConfigNotFoundForConnection;
use BackupManager\Databases\DatabaseTypeNotSupported;
use BackupManager\Filesystems\FilesystemTypeNotSupported;
use BackupManager\Tasks;

/**
 * Class RestoreProcedure
 * @package BackupManager\Procedures
 */
class RestoreProcedure extends Procedure
{
    /**
     * @throws FilesystemTypeNotSupported
     * @throws ConfigFieldNotFound
     * @throws CompressorTypeNotSupported
     * @throws DatabaseTypeNotSupported
     * @throws ConfigNotFoundForConnection
     */
    public function run(string $sourceType, string $sourcePath, string $databaseName, ?string $compression = null): void
    {
        $sequence = new Sequence();

        // begin the life of a new working file
        $localFilesystem = $this->filesystems->get('local');
        $workingFile = $this->getWorkingFile('local', uniqid() . basename($sourcePath));

        // download or retrieve the archived backup file
        $sequence->add(new Tasks\Storage\TransferFile(
            $this->filesystems->get($sourceType),
            $sourcePath,
            $localFilesystem,
            basename($workingFile)
        ));

        // decompress the archived backup
        $compressor = $this->compressors->get($compression);
        $sequence->add(new Tasks\Compression\DecompressFile(
            $compressor,
            $workingFile,
            $this->shellProcessor
        ));
        $workingFile = $compressor->getDecompressedPath($workingFile);

        // restore the database
        $sequence->add(new Tasks\Database\RestoreDatabase(
            $this->databases->get($databaseName),
            $workingFile,
            $this->shellProcessor
        ));

        // cleanup the local copy
        $sequence->add(new Tasks\Storage\DeleteFile(
            $localFilesystem,
            basename($workingFile)
        ));

        $sequence->execute();
    }
}
