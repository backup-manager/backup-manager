<?php namespace BackupManager;

use BackupManager\Compressors\Compressor;
use BackupManager\Databases\Database;
use BackupManager\Filesystems\Filesystem;
use BackupManager\Filesystems\RemoteFile;

class Backup
{
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

    /**
     * @param RemoteFile[] $remoteFiles
     * @return void
     */
    public function run(array $remoteFiles) {
        $workingFile = new File;
        $this->database->dump($workingFile);
        $this->compressor->compress($workingFile);
        $compressedWorkingFile = $this->compressor->compressedFile($workingFile);
        /** @var RemoteFile $remoteFile */
        foreach ($remoteFiles as $remoteFile) {
            $compressedFile = $this->compressor->compressedFile($remoteFile->file());
            $this->filesystem->writeStream(
                $remoteFile->provider(), $compressedFile->path(),
                $this->filesystem->readStream('local', $compressedWorkingFile->path())
            );
        }

        // Clean up
        $this->filesystem->delete('local', $workingFile->path());
        $this->filesystem->delete('local', $compressedWorkingFile->path());
    }
}