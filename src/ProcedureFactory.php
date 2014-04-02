<?php namespace BigName\DatabaseBackup;

use BigName\DatabaseBackup\Factories\ArchiverFactory;
use BigName\DatabaseBackup\Factories\CommandFactory;
use BigName\DatabaseBackup\Factories\ConnectionFactory;
use BigName\DatabaseBackup\Factories\FilesystemFactory;
use BigName\DatabaseBackup\Procedures\BackupProcedure;

/**
 * Class ProcedureFactory
 * @package BigName\DatabaseBackup
 */
class ProcedureFactory
{
    /**
     * @var
     */
    private $commandFactory;

    public function __construct(Config $config)
    {
        $this->commandFactory = new CommandFactory(new FilesystemFactory($config), new ArchiverFactory(), $config);
    }

    /**
     * @return BackupProcedure
     */
    public function makeBackupProcedure()
    {
        return new BackupProcedure($this->commandFactory);
    }
} 
