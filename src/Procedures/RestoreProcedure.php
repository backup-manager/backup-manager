<?php namespace BigName\DatabaseBackup\Procedures;

use BigName\DatabaseBackup\Commands;

class RestoreProcedure extends Procedure
{
    public function run($sourceType, $sourcePath, $databaseName)
    {
        $localPath = $this->getFilename($sourcePath);
        $decompressedFile = preg_replace('/\.gz$/', '', $localPath);

        $this->add(new Commands\Storage\TransferFile(
            $this->filesystemProvider->getType($sourceType), $sourcePath,
            $this->filesystemProvider->getType('local'), $localPath
        ));
        $this->add(new Commands\Archiving\GunzipFile($localPath, $this->shellProcessor));
        $this->add(new Commands\Database\RestoreDatabase($this->databaseProvider->getType($databaseName), $decompressedFile, $this->shellProcessor));
        $this->add(new Commands\Storage\DeleteFile($this->filesystemProvider->getType('local'), $decompressedFile));

        $this->execute();
    }

    private function getFilename($path)
    {
        return basename($path);
    }
} 
