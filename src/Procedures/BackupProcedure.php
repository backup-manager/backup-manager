<?php namespace BackupManager\Procedures;

use BackupManager\Tasks;

/**
 * Class BackupProcedure
 * @package BackupManager\Procedures
 */
class BackupProcedure extends Procedure {

    /**
     * @param string $database
     * @param \BackupManager\Filesystems\Destination[] $destinations
     * @param string $compression
     * @throws \BackupManager\Filesystems\FilesystemTypeNotSupported
     * @throws \BackupManager\Config\ConfigFieldNotFound
     * @throws \BackupManager\Compressors\CompressorTypeNotSupported
     * @throws \BackupManager\Databases\DatabaseTypeNotSupported
     * @throws \BackupManager\Config\ConfigNotFoundForConnection
     */
    public function run($database, array $destinations, $compression) {
        $sequence = new Sequence;

        // begin the life of a new working file
        $localFilesystem = $this->filesystems->get('local');
        $workingFile = $this->getWorkingFile('local');

        // dump the database
        $sequence->add(new Tasks\Database\DumpDatabase(
            $this->databases->get($database),
            $workingFile,
            $this->shellProcessor
        ));

        // archive the dump
        $compressor = $this->compressors->get($compression);
        $sequence->add(new Tasks\Compression\CompressFile(
            $compressor,
            $workingFile,
            $this->shellProcessor
        ));
        $workingFile = $compressor->getCompressedPath($workingFile);

        // upload the archive
        foreach ($destinations as $destination) {
            $sequence->add(new Tasks\Storage\TransferFile(
                $localFilesystem, basename($workingFile),
                $this->filesystems->get($destination->destinationFilesystem()),
                $compressor->getCompressedPath($destination->destinationPath())
            ));
        }

        // cleanup the local archive
        $sequence->add(new Tasks\Storage\DeleteFile(
            $localFilesystem,
            basename($workingFile)
        ));

        $sequence->execute();
    }
}
