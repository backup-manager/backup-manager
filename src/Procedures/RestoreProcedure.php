<?php namespace BigName\DatabaseBackup\Procedures; 

class RestoreProcedure extends Procedure
{
    public function run($storageName, $sourcePath, $databaseName)
    {
        $this->add($this->factory->makeRetrieveFileCommand($storageName, $sourcePath));

        $this->execute();
    }
} 
