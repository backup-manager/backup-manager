<?php namespace BackupManager;

use BackupManager\Compressors\Compressor;
use BackupManager\Databases\Database;
use BackupManager\Filesystems\Filesystem;
use BackupManager\Filesystems\RemoteFile;

class Restore {

    /** @var Database */
    private $database;
    /** @var Filesystem */
    private $filesystem;
    /** @var Compressor */
    private $compressor;

    public function __construct(Database $database, Filesystem $filesystem, Compressor $compressor) {
        $this->database = $database;
        $this->filesystem = $filesystem;
        $this->compressor = $compressor;
    }

    public function run(RemoteFile $remoteFile) {
        $unique = uniqid();
        $workingFile = new File("{$unique}_{$remoteFile->path()}");
        $this->filesystem->writeStream(
            'local', $workingFile->path(),
            $this->filesystem->readStream($remoteFile->provider(), $remoteFile->path())
        );
        $this->compressor->decompress($workingFile);
        $decompressedFile = $this->compressor->decompressedFile($workingFile);
        $this->database->restore($decompressedFile);

        // Clean up
        $this->filesystem->delete('local', $workingFile->path());
        $this->filesystem->delete('local', $decompressedFile->path());
    }
}