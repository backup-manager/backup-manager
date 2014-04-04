<?php namespace BigName\DatabaseBackup\Procedures;

use BigName\DatabaseBackup\Config;
use BigName\DatabaseBackup\Commands\CommandFactory;
use BigName\DatabaseBackup\Filesystems;
use BigName\DatabaseBackup\Filesystems\FilesystemProvider;
use BigName\DatabaseBackup\Sequence;

/**
 * Class ProcedureFactory
 * @package BigName\DatabaseBackup
 */
class ProcedureFactory
{
    private $commandFactory;

    public function __construct(Config $databaseConfig, Config $storageConfig)
    {
        $filesystemProvider = new FilesystemProvider($storageConfig);

        $filesystemProvider->addFilesystem(new Filesystems\LocalFilesystem());
        $filesystemProvider->addFilesystem(new Filesystems\AwsS3Filesystem());

        $this->commandFactory = new CommandFactory($filesystemProvider, $databaseConfig);
    }

    /**
     * @return BackupProcedure
     */
    public function makeBackupProcedure()
    {
        return new BackupProcedure($this->commandFactory, new Sequence);
    }
} 
