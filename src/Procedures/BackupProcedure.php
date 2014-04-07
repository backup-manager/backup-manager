<?php namespace BigName\DatabaseBackup\Procedures;

use BigName\DatabaseBackup\Commands;

class BackupProcedure extends Procedure
{
    public function run($databaseName, $destinationType, $destinationPath)
    {
        $tempFile = $this->getTempFilename();
        $compressedTempFile = "{$tempFile}.gz";

        $this->add(new Commands\Database\DumpDatabase($this->databaseProvider->getType($databaseName), $tempFile, $this->shellProcessor));
        $this->add(new Commands\Archiving\GzipFile($tempFile, $this->shellProcessor));
        $this->add(new Commands\Storage\TransferFile(
            $this->filesystemProvider->getType('local'), $compressedTempFile,
            $this->filesystemProvider->getType($destinationType), $destinationPath
        ));
        $this->add(new Commands\Storage\DeleteFile($this->filesystemProvider->getType('local'), $compressedTempFile));

        $this->execute();
    }

    private function getTempFilename()
    {
        return sprintf('%s.sql', uniqid());
    }
}
