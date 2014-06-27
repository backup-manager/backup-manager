<?php namespace BigName\BackupManager\Procedures;

use BigName\BackupManager\Tasks;

/**
 * Class BackupProcedure
 * @package BigName\BackupManager\Procedures
 */
class BackupProcedure extends Procedure
{
    /**
     * @param $database
     * @param $destination
     * @param $destinationPath
     * @param $compression
     * @throws \BigName\BackupManager\Filesystems\FilesystemTypeNotSupported
     * @throws \BigName\BackupManager\Config\ConfigFieldNotFound
     * @throws \BigName\BackupManager\Compressors\CompressorTypeNotSupported
     * @throws \BigName\BackupManager\Databases\DatabaseTypeNotSupported
     * @throws \BigName\BackupManager\Config\ConfigNotFoundForConnection
     */
    public function run($database, $destination, $destinationPath, $compression)
    {
        // begin the life of a new working file
        $localFilesystem = $this->filesystems->get('local');
        $workingFile = $this->getWorkingFile('local');

        // dump the database
        $this->add(new Tasks\Database\DumpDatabase(
            // database connection
            $this->databases->get($database),
            // output file path
            $workingFile,
            // shell command processor
            $this->shellProcessor
        ));

        // archive the dump
        $compressor = $this->compressors->get($compression);
        $this->add(new Tasks\Compression\CompressFile(
            // compression type
            $compressor,
            // source file path
            $workingFile,
            // shell command processor
            $this->shellProcessor
        ));
        $workingFile = $compressor->getCompressedPath($workingFile);

        // upload the archive
        $this->add(new Tasks\Storage\TransferFile(
            // source fs and path
            $localFilesystem, basename($workingFile),
            // destination fs and path
            $this->filesystems->get($destination), $compressor->getCompressedPath($destinationPath)
        ));
        // cleanup the local archive
        $this->add(new Tasks\Storage\DeleteFile(
            // storage fs
            $localFilesystem,
            // path
            basename($workingFile)
        ));

        $this->execute();
    }
}
