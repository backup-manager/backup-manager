<?php namespace BigName\DatabaseBackup\Procedures;

class BackupProcedure extends Procedure
{
    public function run($databaseName, $storageName, $destinationPath)
    {
        $tempFile = $this->getTempFilename();
        $compressedTempFile = "{$tempFile}.gz";

        $this->add($this->factory->makeDumpDatabaseCommand($databaseName, $tempFile));
        $this->add($this->factory->makeZipFileCommand($tempFile));
        $this->add($this->factory->makeSaveFileCommand($storageName, $compressedTempFile, $destinationPath));
        $this->add($this->factory->makeDeleteFileCommand('local', $compressedTempFile));

        $this->execute();
    }

    private function getTempFilename()
    {
        return sprintf('%s.sql', uniqid());
    }
}
