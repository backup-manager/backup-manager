<?php namespace BigName\DatabaseBackup\Procedures;

class BackupProcedure extends Procedure
{
    public function run($databaseName, $storageName, $destinationPath)
    {
        $localFilename = __DIR__.'/../../working/test.sql';
        $archivedFilename = $localFilename . '.gz';

        $this->add($this->commandFactory->makeDumpDatabaseCommand($databaseName, $localFilename));
        $this->add($this->commandFactory->makeZipCommand($localFilename));
        $this->add($this->commandFactory->makeSaveFileCommand($storageName, $archivedFilename, $destinationPath));
        $this->add($this->commandFactory->makeDeleteFileCommand('local', $archivedFilename));

        $this->execute();
    }
}
