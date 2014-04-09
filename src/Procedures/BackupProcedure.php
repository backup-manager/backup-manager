<?php namespace BigName\BackupManager\Procedures;

use BigName\BackupManager\Commands;

class BackupProcedure extends Procedure
{
    public function run($database, $destination, $destinationPath, $compression)
    {
        // begin the life of a new working file
        $workingFile = $this->getWorkingFile();

        // dump the database
        $this->add(new Commands\Database\DumpDatabase(
            // database connection
            $this->database->get($database),
            // output file path
            $workingFile,
            // shell command processor
            $this->shellProcessor
        ));

        // archive the dump
        $compressor = $this->compressor->get($compression);
        $this->add(new Commands\Compression\CompressFile(
            // compression type
            $compressor,
            // source file path
            $workingFile,
            // shell command processor
            $this->shellProcessor
        ));
        $workingFile = $compressor->getCompressedPath($workingFile);

        // upload the archive
        $this->add(new Commands\Storage\TransferFile(
            // source fs and path
            $this->filesystem->get('local'), $workingFile,
            // destination fs and path
            $this->filesystem->get($destination), $compressor->getCompressedPath($destinationPath)
        ));

        // cleanup the local archive
        $this->add(new Commands\Storage\DeleteFile(
            // storage fs
            $this->filesystem->get('local'),
            // path
            $workingFile
        ));

        $this->execute();
    }

    private function getWorkingFile()
    {
        return sprintf('%s.sql', uniqid());
    }
}
