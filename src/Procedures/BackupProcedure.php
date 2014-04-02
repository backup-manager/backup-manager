<?php namespace BigName\DatabaseBackup\Procedures;

class BackupProcedure extends Procedure
{
    public function run($databaseName, $storageName, $destinationPath)
    {
        $workingPath = $this->getWorkingPath();
        $archivedFilename = "{$workingPath}.gz";

        $this->add($this->factory->makeDumpDatabaseCommand($databaseName, $workingPath));
        $this->add($this->factory->makeZipFileCommand($workingPath));
        $this->add($this->factory->makeSaveFileCommand($storageName, $archivedFilename, $destinationPath));
        $this->add($this->factory->makeDeleteFileCommand('local', $archivedFilename));

        $this->runSequence();
    }

    private function getWorkingPath()
    {
        return sprintf('%s/../../working/%s.sql', __DIR__, uniqid());
    }
}
