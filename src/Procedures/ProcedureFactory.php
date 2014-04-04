<?php namespace BigName\DatabaseBackup\Procedures;

use BigName\DatabaseBackup\Config;
use BigName\DatabaseBackup\Commands\CommandFactory;
use BigName\DatabaseBackup\Filesystems;
use BigName\DatabaseBackup\Filesystems\FilesystemProvider;

/**
 * Class ProcedureFactory
 * The purpose of this class is currently a bit unclear. Obviously, the filesystem
 * provider should be injected.. However, this is sort of a "facade" interface for
 * the user and we don't want the user to be injecting a bunch of shit to make it
 * all work. So, for now we're going with this and as we build it out, it'll make
 * itself clear what it "should" be.
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
