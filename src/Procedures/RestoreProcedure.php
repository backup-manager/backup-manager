<?php namespace BigName\BackupManager\Procedures;

use BigName\BackupManager\Commands;

/**
 * Class RestoreProcedure
 * @package BigName\BackupManager\Procedures
 */
class RestoreProcedure extends Procedure
{
    /**
     * @param $sourceType
     * @param $sourcePath
     * @param $databaseName
     * @param null $compression
     * @throws \BigName\BackupManager\Filesystems\FilesystemTypeNotSupported
     * @throws \BigName\BackupManager\Config\ConfigFieldNotFound
     * @throws \BigName\BackupManager\Compressors\CompressorTypeNotSupported
     * @throws \BigName\BackupManager\Databases\DatabaseTypeNotSupported
     * @throws \BigName\BackupManager\Config\ConfigNotFoundForConnection
     */
    public function run($sourceType, $sourcePath, $databaseName, $compression = null)
    {
        // begin the life of a new working file
        $workingFile = $this->getWorkingFile($sourcePath);

        // download or retrieve the archived backup file
        $this->add(new Commands\Storage\TransferFile(
            $this->filesystem->get($sourceType), $sourcePath,
            $this->filesystem->get('local'), $workingFile
        ));

        // decompress the archived backup
        $compressor = $this->compressor->get($compression);

        $this->add(new Commands\Compression\DecompressFile(
            $compressor,
            $workingFile,
            $this->shellProcessor
        ));
        $workingFile = $compressor->getDecompressedPath($workingFile);

        // restore the database
        $this->add(new Commands\Database\RestoreDatabase(
            $this->database->get($databaseName),
            $workingFile,
            $this->shellProcessor
        ));

        // cleanup the local copy
        $this->add(new Commands\Storage\DeleteFile(
            $this->filesystem->get('local'),
            $workingFile
        ));

        $this->execute();
    }

    /**
     * @param $path
     * @return string
     */
    private function getWorkingFile($path)
    {
        return basename($path);
    }
} 
