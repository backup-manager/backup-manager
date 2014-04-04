<?php namespace BigName\DatabaseBackup\Procedures;

use BigName\DatabaseBackup\Commands;
use BigName\DatabaseBackup\Connections\MysqlConnection;

class RestoreProcedure extends Procedure
{
    public function run($sourceType, $sourcePath, $databaseName)
    {
        $localPath = $this->getFilename($sourcePath);
        $decompressedFile = preg_replace('/\.gz$/', '', $localPath);

        $mysql = new MysqlConnection(
            $this->databaseConfig->get($databaseName, 'host'),
            $this->databaseConfig->get($databaseName, 'port'),
            $this->databaseConfig->get($databaseName, 'user'),
            $this->databaseConfig->get($databaseName, 'pass'),
            $this->databaseConfig->get($databaseName, 'database')
        );

        $this->add(new Commands\Storage\TransferFile(
            $this->filesystemProvider->getType($sourceType), $sourcePath,
            $this->filesystemProvider->getType('local'), $localPath
        ));
        $this->add(new Commands\Archiving\GunzipFile($localPath, $this->shellProcessor));
        $this->add(new Commands\Database\Mysql\RestoreDatabase($mysql, $decompressedFile, $this->shellProcessor));
        $this->add(new Commands\Storage\DeleteFile($this->filesystemProvider->getType('local'), $decompressedFile));

        $this->execute();
    }

    private function getFilename($path)
    {
        return basename($path);
    }
} 
