<?php namespace BigName\BackupManager\Procedures;

use BigName\BackupManager\Commands;

class RestoreProcedure extends Procedure
{
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

    private function getWorkingFile($path)
    {
        return basename($path);
    }
} 
