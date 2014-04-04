<?php namespace BigName\DatabaseBackup\Procedures;

use BigName\DatabaseBackup\Commands\Archiving\GzipFile;
use BigName\DatabaseBackup\Commands\Database\Mysql\DumpDatabase;
use BigName\DatabaseBackup\Commands\Storage\DeleteFile;
use BigName\DatabaseBackup\Commands\Storage\TransferFile;
use BigName\DatabaseBackup\Connections\MysqlConnection;

class BackupProcedure extends Procedure
{
    public function run($databaseName, $destinationConnectionName, $destinationPath)
    {
        $tempFile = $this->getTempFilename();
        $compressedTempFile = "{$tempFile}.gz";

        $mysql = new MysqlConnection(
            $this->databaseConfig->get($databaseName, 'host'),
            $this->databaseConfig->get($databaseName, 'port'),
            $this->databaseConfig->get($databaseName, 'user'),
            $this->databaseConfig->get($databaseName, 'pass'),
            $this->databaseConfig->get($databaseName, 'database')
        );

        $this->add(new DumpDatabase($mysql, $tempFile));
        $this->add(new GzipFile($tempFile));
        $this->add(new TransferFile(
            $this->filesystemProvider->getType('local'), $compressedTempFile,
            $this->filesystemProvider->getType($destinationConnectionName), $destinationPath
        ));
        $this->add(new DeleteFile($this->filesystemProvider->getType('local'), $compressedTempFile));

        $this->execute();
    }

    private function getTempFilename()
    {
        return sprintf('%s.sql', uniqid());
    }
}
