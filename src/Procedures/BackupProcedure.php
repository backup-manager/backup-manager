<?php namespace BackupManager\Procedures;

use BackupManager\Tasks;

/**
 * Class BackupProcedure
 *
 * @package BackupManager\Procedures
 */
class BackupProcedure extends Procedure {
    /** @var Sequence */
    private $sequence;

    /** @var string */
    private $backup_database;

    /** @var array */
    private $backup_destination_filesystems = [];

    /** @var string */
    private $backup_destination_path;

    /** @var string */
    private $backup_compression;

    /**
     * @param string $database
     * @param string $destination
     * @param string $destinationPath
     * @param string $compression
     *
     * @throws \BackupManager\Filesystems\FilesystemTypeNotSupported
     * @throws \BackupManager\Config\ConfigFieldNotFound
     * @throws \BackupManager\Compressors\CompressorTypeNotSupported
     * @throws \BackupManager\Databases\DatabaseTypeNotSupported
     * @throws \BackupManager\Config\ConfigNotFoundForConnection
     */
    public function run($database, $destination, $destinationPath, $compression) {
        $this->setDatabase($database);
        $this->addDestinationFilesystem($destination);
        $this->setDestinationPath($destinationPath);
        $this->setCompression($compression);
        $this->execute();
    }

    /**
     * @param string $database
     *
     * @return void
     */
    public function setDatabase($database) {
        $this->backup_database = $database;
    }

    /**
     * @param string $destination
     *
     * @return void
     */
    public function addDestinationFilesystem($destination) {
        if (!array_search($destination, $this->backup_destination_filesystems)) {
            $this->backup_destination_filesystems[] = $destination;
        }
    }

    /**
     * @param string $destination_path
     *
     * @return void
     */
    public function setDestinationPath($destination_path) {
        $this->backup_destination_path = $destination_path;
    }

    /**
     * @param string $compression
     *
     * @return void
     */
    public function setCompression($compression) {
        $this->backup_compression = $compression;
    }

    /**
     * @return void
     *
     * @throws \BackupManager\Compressors\CompressorTypeNotSupported
     * @throws \BackupManager\Databases\DatabaseTypeNotSupported
     * @throws \BackupManager\Filesystems\FilesystemTypeNotSupported
     */
    public function execute(){
        $this->sequence = new Sequence();

        // begin the life of a new working file
        $localFilesystem = $this->filesystems->get('local');
        $workingFile     = $this->getWorkingFile('local');

        // dump the database
        $this->sequence->add(new Tasks\Database\DumpDatabase(
            $this->databases->get($this->backup_database),
            $workingFile,
            $this->shellProcessor
        ));

        // archive the dump
        $compressor = $this->compressors->get($this->backup_compression);
        $this->sequence->add(new Tasks\Compression\CompressFile(
            $compressor,
            $workingFile,
            $this->shellProcessor
        ));
        $workingFile = $compressor->getCompressedPath($workingFile);

        // upload the archive
        foreach ($this->backup_destination_filesystems as $destination) {
            $this->sequence->add(new Tasks\Storage\TransferFile(
                $localFilesystem, basename($workingFile),
                $this->filesystems->get($destination), $compressor->getCompressedPath($this->backup_destination_path)));
        }

        // cleanup the local archive
        $this->sequence->add(new Tasks\Storage\DeleteFile(
            $localFilesystem,
            basename($workingFile)
        ));

        $this->sequence->execute();
    }
}
