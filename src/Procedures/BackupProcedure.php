<?php namespace BigName\DatabaseBackup\Procedures;

use BigName\DatabaseBackup\Commands;
use BigName\DatabaseBackup\Connections\MysqlConnection;

class BackupProcedure extends Procedure
{
    public function run($databaseName, $destinationType, $destinationPath)
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

        $this->add(new Commands\Database\Mysql\DumpDatabase($mysql, $tempFile, $this->shellProcessor));
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
