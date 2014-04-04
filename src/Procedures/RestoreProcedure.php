<?php namespace BigName\DatabaseBackup\Procedures; 

use BigName\DatabaseBackup\Commands\Archiving\GunzipFile;
use BigName\DatabaseBackup\Commands\Database\Mysql\RestoreDatabase;
use BigName\DatabaseBackup\Commands\Storage\DeleteFile;
use BigName\DatabaseBackup\Commands\Storage\TransferFile;
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

        $this->add(new TransferFile(
            $this->filesystemProvider->getType($sourceType), $sourcePath,
            $this->filesystemProvider->getType('local'), $localPath
        ));
        $this->add(new GunzipFile($localPath, $this->shellProcessor));
        $this->add(new RestoreDatabase($mysql, $decompressedFile, $this->shellProcessor));
        $this->add(new DeleteFile($this->filesystemProvider->getType('local'), $decompressedFile));

        $this->execute();
    }

    private function getFilename($path)
    {
        return basename($path);
    }
} 
