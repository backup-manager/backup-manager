<?php namespace BackupManager;

use BackupManager\Compressors\Compressor;
use BackupManager\Databases\Database;
use BackupManager\Filesystems\Filesystem;

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
        $workingFile = new File(null, $this->filesystem->root('local'));
        $this->database->dump($workingFile);
        $this->compressor->compress($workingFile);
        $compressedWorkingFile = $this->compressor->compressedFile($workingFile);
        /** @var RemoteFile $remoteFile */
        foreach ($remoteFiles as $remoteFile) {
            $compressedFile = $this->compressor->compressedFile($remoteFile->file());
            $this->filesystem->writeStream(
                $remoteFile->provider(), $compressedFile->filePath(),
                $this->filesystem->readStream('local', $compressedWorkingFile->filePath())
            );
        }

        // Clean up
        $this->filesystem->delete('local', $compressedWorkingFile->filePath());
    }
}